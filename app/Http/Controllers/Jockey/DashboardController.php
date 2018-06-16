<?php

namespace App\Http\Controllers\Jockey;

use App\Http\Controllers\Controller;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    	// with coaches, upcomingActivities, racingExcellences
    	$jockey = Jockey::with([
    		'coaches',
    		'dashboardUpcomingActivities',
    		'dashboardUpcomingActivities.activityLocation',
    		'dashboardRecentActivities',
    		'dashboardRecentActivities.activityLocation',
    		'racingExcellences',
    	])
    	->findOrFail(auth()->user()->id);


    	return view('jockey.dashboard.index', compact('jockey'));
    }
}
