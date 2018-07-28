<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\Coach\NewCoachCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\StorePostFormRequest;
use App\Models\Coach;
use App\Models\Country;
use App\Models\County;
use App\Models\Nationality;
use Carbon\Carbon;
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
    	$coach = Coach::createNew(array_merge($request->only([
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
            'vat_number'
    	]),
            ['date_of_birth' => $request->date_of_birth ? Carbon::createFromFormat('d/m/Y',"{$request->date_of_birth}") : null ]
        ));

        $coach->mileages()->create([
            'year' => now()->year,
            'miles' => $request->mileage ? $request->mileage : 0
        ]);

    	event(new NewCoachCreated($coach));

    	return redirect()->route('coach.show', $coach);
    }

    public function show(Coach $coach)
    {
        
    }
}
