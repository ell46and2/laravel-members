<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\Coach\NewCoachCreated;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function store()
    {
    	$coach = User::createCoach(request()->only([
    		'first_name',
    		'last_name', 
    		'telephone', 
    		'street_address', 
    		'city', 
    		'postcode', 
    		'email',
    	]));

    	event(new NewCoachCreated($coach));

    	return redirect()->route('admin.coach.show', $coach);
    }
}
