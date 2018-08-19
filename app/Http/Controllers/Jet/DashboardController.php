<?php

namespace App\Http\Controllers\Jet;

use App\Http\Controllers\Controller;
use App\Models\CrmRecord;
use App\Models\Jet;
use App\Models\Pdp;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    	$jet = Jet::findOrFail($this->currentUser->id);

    	$pdpAwaitingReview = Pdp::with('jockey')->where('status', 'Awaiting Review')->orderBy('submitted', 'desc')->take(10)->get();

    	$recentCrmRecords = CrmRecord::with('managable')->orderBy('created_at', 'desc')->take(10)->get();

    	return view('jet.dashboard.index', compact('jet', 'pdpAwaitingReview', 'recentCrmRecords'));
    }
}
