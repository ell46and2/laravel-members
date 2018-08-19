<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\UpdateCoachFormRequest;
use App\Http\Resources\UserSelectResource;
use App\Models\Coach;
use App\Models\Country;
use App\Models\County;
use App\Models\Jockey;
use App\Models\Nationality;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function show(Coach $coach)
    {
        $this->authorize('show', $coach);

        $jockeysResource = UserSelectResource::collection(Jockey::active()->with('role')->get());

    	if($this->currentRole === 'admin' || $this->currentUser->id === $coach->id) {
    		$countries = Country::all();
        	$counties = County::all();
        	$nationalities = Nationality::all();

    	   return view('coach.show', compact('coach', 'countries', 'counties', 'nationalities', 'jockeysResource'));
    	}

        if($this->currentRole === 'jockey') {
            $jockey = Jockey::findOrFail($this->currentUser->id);
            // hours training for month and year
            $trainingWithCoachMonth = $coach->trainingTimeWithJockeyThisMonth($jockey->id);
            $trainingWithCoachYear = $coach->trainingTimeWithJockeyThisYear($jockey->id);
            // last 5 recent activities
            $lastFiveActivities = $jockey->activities()
                ->with('location', 'type')
                ->where('coach_id', $coach->id)
                ->where('end', '<', now())
                ->orderBy('end', 'desc')
                ->take(5)
                ->get();

            return view('coach.show', compact('coach', 'jockeysResource', 'trainingWithCoachMonth', 'trainingWithCoachYear', 'lastFiveActivities'));
        }

    	return view('coach.show', compact('coach', 'jockeysResource'));
    }

    public function updateProfile(UpdateCoachFormRequest $request, Coach $coach)
    {
    	$coach->update($request->only([
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
            'vat_number',
            'bio'
    	]));

    	session()->flash('success', "Coach profile updated");

    	return back();
    }

    public function updateStatus(Request $request, Coach $coach)
    {
    	if($request->status === 'deleted' && $coach->jockeys->count()) {
            session()->flash('error', 'You must unassign all Jockey from the Coach before deleting');
            return back();
        }

        $coach->update([
            'status' => $request->status
        ]);

        session()->flash('success', "Coach status updated to {$request->status}.");

        if($request->status === 'deleted') {
            return redirect('admin.dashboard.index');
        }

        return back();
    }
}
