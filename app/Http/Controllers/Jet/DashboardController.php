<?php

namespace App\Http\Controllers\Jet;

use App\Http\Controllers\Controller;
use App\Models\Jet;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    	$jet = Jet::findOrFail($this->currentUser->id);

    	return view('jet.dashboard.index', compact('jet'));
    }
}
