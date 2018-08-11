<?php

namespace App\Http\Controllers\Jockey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jockey\UpdateJockeyFormRequest;
use App\Http\Resources\UserSelectResource;
use App\Jobs\Jockey\NotifyCoachesOfJockeyStatus;
use App\Jobs\RacingExcellence\CheckIfJockeyFormerExternalReParticipant;
use App\Models\Coach;
use App\Models\Country;
use App\Models\County;
use App\Models\Jockey;
use App\Models\Nationality;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Api\ApiGateway;

class JockeyController extends Controller
{
    public function show(Jockey $jockey)
    {
        $this->authorize('profile', $jockey); 
    	 
    	if(!$jockey->approved) {
    		return $this->showUnapproved($jockey);
    	}

    	$jockey->with([
    		'coaches'
    	]);

        $coachesResource = UserSelectResource::collection(Coach::with('role')->get());

    	if($this->currentRole === 'admin' || $this->currentUser->id === $jockey->id) {
    		$countries = Country::all();
        	$counties = County::all();
        	$nationalities = Nationality::all();

            

    	   return view('jockey.show-approved', compact('jockey', 'countries', 'counties', 'nationalities', 'coachesResource'));
    	}

        return view('jockey.show-approved', compact('jockey', 'coachesResource'));
    }

    public function showUnapproved(Jockey $jockey)
    {
    	$countries = Country::all();
        $counties = County::all();
        $nationalities = Nationality::all();

    	return view('jockey.show-unapproved', compact('jockey', 'countries', 'counties', 'nationalities'));
    }

    public function setApi(Request $request, Jockey $jockey)
    {
    	$statsFromAPI = $this->getApiStats($request->api_id);

    	if(!$statsFromAPI) {
    		// return back with error message
    	}

    	$jockey->update([
    		'api_id' => $request->api_id,
    		'wins' => isset($statsFromAPI->careerSummary[0]) ? $statsFromAPI->careerSummary[0]->numberOfWins : null,
            'rides' => isset($statsFromAPI->careerSummary[0]) ? $statsFromAPI->careerSummary[0]->numberOfRides : null,
            'lowest_riding_weight' => isset($statsFromAPI->lowestRidingWeight) ? $statsFromAPI->lowestRidingWeight : null,
            'licence_type' => isset($statsFromAPI->licences[0]) ? $statsFromAPI->licences[0]->licenceType : null,
            'licence_date' => isset($statsFromAPI->licences[0]) ? $statsFromAPI->licences[0]->issueDate : null,
            'prize_money' => isset($statsFromAPI->seasonDetails) ? $this->apiGateway->calcPrizeMoney($statsFromAPI->seasonDetails) : null,
            'associated_content' => 'https://www.britishhorseracing.com/racing/stewards-reports/#!?q=' . $statsFromAPI->name,
            'trainer_name' => $statsFromAPI->trainerName,
    	]); 


        $this->dispatch(new CheckIfJockeyFormerExternalReParticipant($jockey));

        // NOTE: will need to check if they match an external JETS CRM jockey also.

    	return back();
    }

    public function updateProfile(UpdateJockeyFormRequest $request, Jockey $jockey) 
    {
    	$jockey->update($request->only([
            'first_name',
            'last_name',
            'middle_name',
            'alias',
            'address_1',
            'address_2',
            'county_id',
            'country_id',
            'nationality_id',
            'postcode',
            'telephone',
            'twitter_handle',
            'email',
    	]));

    	session()->flash('success', "Jockey profile updated");

    	return back();
    }

    public function updateStatus(Request $request, Jockey $jockey) // form validation
    {
        if($request->status === 'deleted' && $jockey->coaches->count()) {
            session()->flash('error', 'Please unassign the Jockey from any Coaches before deleting');
            return back();
        }

        $jockey->update([
            'status' => $request->status
        ]);

        $this->dispatch(new NotifyCoachesOfJockeyStatus($jockey));
        
        session()->flash('success', "Jockey status updated to {$request->status}.");
        
        if($request->status === 'deleted') {
            return redirect('admin.dashboard.index');
        }

        return back();
    }

    private function getApiStats($apiId)
    {
        $apiGateway = new ApiGateway;

        return $apiGateway->getJockeyStats($apiId);
    }
}
