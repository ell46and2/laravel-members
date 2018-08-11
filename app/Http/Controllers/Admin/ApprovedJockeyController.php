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

        session()->flash('success', "Jockey {$jockey->full_name} approved.");

    	return redirect()->route('jockey.show', $jockey); // Maybe add flash message to say jockey approved OR return json if using vue.
    }

    public function destroy(Jockey $jockey)
    {
        session()->flash('success', "Jockey {$jockey->full_name} declined and removed from system.");
    	
        $jockey->delete();

    	return redirect()->route('admin.jockeys.pending');
    }
}
