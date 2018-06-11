<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Jockey\Account\JockeyApprovedEmail;
use App\Models\Jockey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApprovedJockeyController extends Controller
{
    public function create(Jockey $jockey)
    {
    	$jockey->approve();

    	Mail::to($jockey)->queue(new JockeyApprovedEmail($jockey));

    	return redirect()->route('admin.jockeys.pending'); // Maybe add flash message to say jockey approved OR return json if using vue.
    }
}
