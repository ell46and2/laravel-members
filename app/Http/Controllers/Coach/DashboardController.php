<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationsResource;
use App\Models\Coach;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    	// move to view composer
    	// $notificationsResource = new NotificationsResource(null); 
    	
   

    	
    	$coach = Coach::with([
    		'jockeys',
    		'jockeys.coaches',
    	])
    	->findOrFail(auth()->user()->id);

    	// dd($coach);

    	return view('coach.dashboard.index', compact('coach'));
    }
}
