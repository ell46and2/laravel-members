<?php

namespace App\Http\Controllers\RacingExcellence;

use App\Http\Controllers\Controller;
use App\Http\Resources\RacingExcellence\RaceResource;
use App\Http\Resources\UserSelectResource;
use App\Jobs\RacingExcellence\NotifyCoachAddedToRacingExcellence;
use App\Models\Coach;
use App\Models\RacingExcellence;
use Illuminate\Http\Request;

class RacingExcellenceController extends Controller
{
    public function show(RacingExcellence $racingExcellence)
    {
    	// NOTE: If assigned coach or RE redirect to the results page
        
        if($this->currentUser->isAdmin() || $racingExcellence->coach_id === $this->currentUser->id) {
           return $this->showResults($racingExcellence);
        } 

    	$usersParticpantIds = $this->getUsersParticipants();

    	return view('racing-excellence.show', compact('racingExcellence', 'usersParticpantIds'));
    }

    private function showResults(RacingExcellence $racingExcellence)
    {
        $coachesResource = UserSelectResource::collection(Coach::with('role')->get());
        $raceResource = new RaceResource($racingExcellence);

        return view('racing-excellence.results.create', compact('raceResource', 'coachesResource', 'racingExcellence'));
    }

    private function getUsersParticipants()
    {
    	$usersRole = $this->currentUser->roleName;
    	
    	if($usersRole === 'jockey') {
    		return [$this->currentUser->id];
    	}

    	if($usersRole === 'coach') {
    		return Coach::find($this->currentUser->id)->jockeys->pluck('id')->toArray();
    	}
    }

    public function assignCoach(Request $request, RacingExcellence $racingExcellence)
    {
        if($coach = Coach::find($request->coach_id)) {
           $racingExcellence->update([
                'coach_id' => $request->coach_id
            ]);

           $this->dispatch(new NotifyCoachAddedToRacingExcellence($racingExcellence));

           session()->flash('success', "Coach assigned to race.");

        } else {
            session()->flash('error', "Coach not found. Please try again.");
        }    

        return back();
    }
}
