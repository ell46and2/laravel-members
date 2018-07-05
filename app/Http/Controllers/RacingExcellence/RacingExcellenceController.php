<?php

namespace App\Http\Controllers\RacingExcellence;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\RacingExcellence;
use Illuminate\Http\Request;

class RacingExcellenceController extends Controller
{
    public function show(RacingExcellence $racingExcellence)
    {
    	// NOTE: If assigned coach or RE redirect to the results page

    	$usersParticpantIds = $this->getUsersParticipants();

    	return view('racing-excellence.show', compact('racingExcellence', 'usersParticpantIds'));
    }

    private function getUsersParticipants()
    {
    	$usersRole = auth()->user()->roleName;
    	
    	if($usersRole === 'jockey') {
    		return [auth()->user()->id];
    	}

    	if($usersRole === 'coach') {
    		return Coach::find(auth()->user()->id)->jockeys->pluck('id')->toArray();
    	}
    }
}
