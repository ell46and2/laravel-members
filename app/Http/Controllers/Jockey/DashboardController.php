<?php

namespace App\Http\Controllers\Jockey;

use App\Http\Controllers\Controller;
use App\Models\Jockey;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    	// with coaches, upcomingActivities, racingExcellences
    	$jockey = Jockey::with('coaches', 'upcomingActivities', 'upcomingActivities.activityType', 'racingExcellences')
    		->findOrFail(auth()->user()->id);

    	// dd($jockey);
    	
    	return view('jockey.dashboard.index', compact('jockey'));
    }
}
