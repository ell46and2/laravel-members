<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function store()
    {
    	$activity = auth()->user()->activities()->create(request()->only([
			'start',
        	'duration',
        	'location',
    	]));

    	$activity->addJockeysById(request()->jockeys);

    	// use observable to create 'end' timestamp from 'start' and 'duration'
   	
    	// Need to send notifications to any attached jockeys
    	
    	return redirect()->route('coach.activity.show', $activity);
    }
}
