<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\Coach\NewCoachCreated;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function store() // need to add FormREquest validation
    {
    	$coach = Coach::createNew(request()->only([
    		'first_name',
    		'last_name',
            'gender', 		
    		'address_1', 
    		'address_2',
            'county_id',
            'country_id',
            'nationality_id',
    		'postcode',
            'telephone', 
    		'email',
    	]));

    	event(new NewCoachCreated($coach));

    	return redirect()->route('admin.coach.show', $coach);
    }
}
