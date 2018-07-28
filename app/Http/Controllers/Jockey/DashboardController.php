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
    		'dashboardUpcomingActivities.location',
            'dashboardUpcomingActivities.type',
    		'dashboardRecentActivities',
    		'dashboardRecentActivities.location',
            'dashboardRecentActivities.type',
    		'racingExcellences',
    	])
    	->findOrFail($this->currentUser->id);


    	return view('jockey.dashboard.index', compact('jockey'));
    }
}
