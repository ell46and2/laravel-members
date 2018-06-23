<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationsResource;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    	$notificationsResource = new NotificationsResource(null);

    	return view('coach.dashboard.index', compact('notificationsResource'));
    }
}
