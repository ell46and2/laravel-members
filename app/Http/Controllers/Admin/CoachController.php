<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\Coach\NewCoachCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\StorePostFormRequest;
use App\Models\Coach;
use App\Models\Country;
use App\Models\County;
use App\Models\Nationality;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function create()
    {
        $countries = Country::all();
        $counties = County::all();
        $nationalities = Nationality::all();

        return view('admin.coach.create', compact('countries', 'counties', 'nationalities'));
    }

    public function store(StorePostFormRequest $request)
    {
    	$coach = Coach::createNew($request->only([
    		'first_name',
    		'last_name',
            'middle_name',
            'date_of_birth',
            'gender', 		
    		'address_1', 
    		'address_2',
            'county_id',
            'country_id',
            'nationality_id',
    		'postcode',
            'telephone',
            'twitter_handle', 
    		'email',
            'mileage',
            'vat_number'
    	]));

    	event(new NewCoachCreated($coach));

    	return redirect()->route('admin.coach.show', $coach);
    }

    public function show(Coach $coach)
    {
        
    }
}
