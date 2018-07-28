<?php

namespace App\Http\Controllers\RacingExcellence;

use App\Http\Controllers\Controller;
use App\Http\Requests\RacingExcellence\RacingExcellenceResultsFormRequest;
use App\Http\Resources\RacingExcellence\ParticipantResource;
use App\Jobs\RacingExcellence\Results\NotifyAllRacingResults;
use App\Jobs\RacingExcellence\Results\NotifyRacingResultUpdated;
use App\Jobs\RacingExcellence\Results\PostDivisionResults;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
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
    		// 'place' => $request->place === 'dnf' ? null : $request->place,
      //       'completed_race' => $request->place === 'dnf' ? false : true,
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

        // if division completed post to api
        $this->checkIfDivisionResultsCompleted($participant);

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
        return;
    }

    private function checkIfDivisionResultsCompleted(RacingExcellenceParticipant $participant)
    {
        $division = $participant->division;

        if(!$division->participants()->where('place_points', null)->count()) {
           $this->postRaceResults($division);
        }
    }

    private function postRaceResults(RacingExcellenceDivision $division)
    {
        $race = $division->racingExcellence;
        $participants = $division->participants;

        $body = new \stdClass();

        $results = [];

        foreach ($participants as $participant) {
            $result = new \stdClass();
            $result->animalId = $participant->api_animal_id;
            $result->score = $participant->total_points;

            array_push($results, $result);
        }

        $body->coachName = $race->coach->full_name;
        $body->results = $results;

        // dd(json_encode($body));

        // dispatch job to post results
        $this->dispatch(new PostDivisionResults($division, $body));
    }
}
