<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Invoice;
use App\Models\Jockey;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    	$admin = Admin::findOrFail($this->currentUser->id);

    	$jockeysAwaitingApproval = Jockey::awaitingApproval()->get();

    	$mostActiveCoaches = Coach::mostActive()->take(6);

    	$invoicesAwaitingReview = Invoice::with('coach')->where('status', 'pending review')->get();

		return view('admin.dashboard.index', compact(
			'admin', 
			'jockeysAwaitingApproval', 
			'mostActiveCoaches', 
			'invoicesAwaitingReview'
		));
    }
}
