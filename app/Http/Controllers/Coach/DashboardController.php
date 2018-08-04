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
    	$coach = Coach::with([
    		'jockeys',
    		'jockeys.coaches',
            'invoices',
    	])
    	->findOrFail($this->currentUser->id);

        // take 5 order by most active
        // QUESTION: Whats doe most active mean?
        $jockeys = $coach->jockeys; 

    	$latestOpenInvoice = $coach->latestOpenInvoice;
    	$lastInvoice = $coach->lastSubmittedInvoice(); 

    	return view('coach.dashboard.index', compact('coach', 'lastInvoice', 'latestOpenInvoice'));
    }
}
