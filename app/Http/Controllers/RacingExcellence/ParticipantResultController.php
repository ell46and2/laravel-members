<?php

namespace App\Http\Controllers\RacingExcellence;

use App\Http\Controllers\Controller;
use App\Http\Requests\RacingExcellence\RacingExcellenceResultsFormRequest;
use App\Http\Resources\RacingExcellence\ParticipantResource;
use App\Jobs\RacingExcellence\Results\NotifyAllRacingResults;
use App\Jobs\RacingExcellence\Results\NotifyRacingResultUpdated;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceParticipant;
use Illuminate\Http\Request;

class ParticipantResultController extends Controller
{
    public function update(RacingExcellenceResultsFormRequest $request, RacingExcellenceParticipant $participant) // add form request validation
    {
    	$racingExcellence = $participant->racingExcellence;

    	$this->authorize('addParticipantResults', $racingExcellence);

    	// NOTE: need to handle race not completed 

    	$participant->update([
    		'place' => $request->place === 'dnf' ? null : $request->place,
            'completed_race' => $request->place === 'dnf' ? false : true,
	    	'presentation_points' => $request->presentation_points,
	    	'professionalism_points' => $request->professionalism_points,
	    	'coursewalk_points' => $request->coursewalk_points,
	    	'riding_points' => $request->riding_points,
	    	'feedback' => $request->feedback,
    	]);

    	
    	if($racingExcellence->completed) {
    		$this->notifyJockeyOfRaceResultUpdate($participant);
    	}

    	$this->checkIfRaceResultsCompleted($racingExcellence);

    	return new ParticipantResource($participant->fresh());
    }

    private function notifyJockeyOfRaceResultUpdate(RacingExcellenceParticipant $participant)
    {
    	if($participant->jockey_id !== null) {
    		$this->dispatch(new NotifyRacingResultUpdated($participant));
    	} 	
    }

    private function checkIfRaceResultsCompleted(RacingExcellence $racingExcellence)
    {
    	if(!$racingExcellence->participants->where('place_points', null)->count()) {
    		$racingExcellence->completed = true;
    		$racingExcellence->save();
    		$this->dispatch(new NotifyAllRacingResults($racingExcellence));
    	}
    	// get number of racingExcellence participants where place equals null 
    	// or completed_race equals false
    	// if greater than zero just return
    	// else queue job to notify all jockeys of race results being added.
    }
}
