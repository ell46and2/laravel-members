<?php

namespace App\Http\Controllers\Coach;

use App\Events\Coach\Activity\NewActivityCreated;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request) // add form request validation
    {
    	$activity = auth()->user()->activities()->create([
			'start' => Carbon::parse("{$request->start_date} {$request->start_time}"),
        	'duration' => $request->duration,
        	'location' => $request->location
    	]);

    	$activity->addJockeysById(request()->jockeys);

    	// use observable to create 'end' timestamp from 'start' and 'duration'
   	
    	// Need to send notifications to any attached jockeys
    	event(new NewActivityCreated($activity));
    	
    	return redirect()->route('coach.activity.show', $activity);
    }
}
