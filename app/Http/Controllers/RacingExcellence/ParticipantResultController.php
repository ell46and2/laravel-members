<?php

namespace App\Http\Controllers\RacingExcellence;

use App\Http\Controllers\Controller;
use App\Http\Requests\RacingExcellence\RacingExcellenceResultsFormRequest;
use App\Models\RacingExcellenceParticipant;
use Illuminate\Http\Request;

class ParticipantResultController extends Controller
{
    public function update(RacingExcellenceResultsFormRequest $request, RacingExcellenceParticipant $participant) // add form request validation
    {
    	$participant->update([
    		'place' => $request->place,
	    	'presentation_points' => $request->presentation_points,
	    	'professionalism_points' => $request->professionalism_points,
	    	'coursewalk_points' => $request->coursewalk_points,
	    	'riding_points' => $request->riding_points,
	    	'feedback' => $request->feedback,
    	]);

    	// dd($participant->fresh());


    	// return participantResource
    }
}
