<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Resources\RacingExcellence\RaceResource;
use App\Http\Resources\UserSearchResource;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use Illuminate\Http\Request;

class RacingExcellenceController extends Controller
{
    public function edit(RacingExcellence $racingExcellence)
    {
    	// policy assigned coach only
    	$this->authorize('coachEdit', $racingExcellence);

    	$jockeysResource = UserSearchResource::collection(Jockey::all()->sortBy('full_name'));
    	$raceResource = new RaceResource($racingExcellence);

    	return view('coach.racing-excellence.edit', compact('raceResource', 'jockeysResource', 'racingExcellence'));
    }
}
