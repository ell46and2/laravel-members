<?php

namespace App\Http\Controllers\RacingExcellence;

use App\Http\Controllers\Controller;
use App\Http\Resources\RacingExcellence\ParticipantResource;
use App\Http\Resources\UserResource;
use App\Jobs\RacingExcellence\NotifyAddedToRacingExcellence;
use App\Jobs\RacingExcellence\NotifyRemovedFromRacingExcellence;
use App\Models\Jockey;
use App\Models\RacingExcellenceDivision;
use App\Models\RacingExcellenceParticipant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function jockey(Jockey $jockey)
    {
    	return new UserResource($jockey);
    }

    public function create(Request $request, RacingExcellenceDivision $racingExcellenceDivision) // add form request
    {
    	// form request validation - id must be an exisitng jockeys id
    	// check jockey isn't already in the division
    	
    	$participant = $racingExcellenceDivision->addJockeyById($request->id);

    	if(!$this->existsInBothDivisions($participant)) {
    		$this->dispatch(new NotifyAddedToRacingExcellence($participant));
    	}
    	// notify jockey - added to racing Excellence

    	return new ParticipantResource($participant);
    }

    public function externalCreate(Request $request, RacingExcellenceDivision $racingExcellenceDivision)
    {
    	$participant = $racingExcellenceDivision->addExternalParticipant($request->name);

    	return new ParticipantResource($participant);
    }

    public function destroy(RacingExcellenceParticipant $racingExcellenceParticipant)
    {
    	// policy admin and assigned coach only
    	
    	// notify if a jockey and they don't exist in another division.
    	if($racingExcellenceParticipant->jockey_id && !$this->existsInBothDivisions($racingExcellenceParticipant)) {
    		$this->dispatch(new NotifyRemovedFromRacingExcellence(
    			$racingExcellenceParticipant->jockey_id, 
    			$racingExcellenceParticipant->racingExcellence
    			)
    		);
    	}

    	$racingExcellenceParticipant->delete();


    	return response()->json(null, 200);
    }

    private function existsInBothDivisions(RacingExcellenceParticipant $racingExcellenceParticipant)
    {
    	if(!$racingExcellenceParticipant->jockey_id) return false;

		$timesInRace = $racingExcellenceParticipant
			->racingExcellence
			->participants
			->where('jockey_id', $racingExcellenceParticipant->jockey_id)
			->count();  	

		return $timesInRace === 2;
    }
}
