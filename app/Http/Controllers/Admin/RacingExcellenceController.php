<?php

namespace App\Http\Controllers\Admin;


use App\Events\Admin\RacingExcellence\NewRacingExcellenceCreated;
use App\Http\Controllers\Controller;
use App\Models\RacingExcellence;
use Illuminate\Http\Request;

class RacingExcellenceController extends Controller
{
    public function store(Request $request)
    {
    	// dd(request()->divisions);
    	$racingExcellence = RacingExcellence::createRace($request);

    	event(new NewRacingExcellenceCreated($racingExcellence));

  		// dd($racingExcellence->participants->count());
    }
}
