<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\Jet\NewJetCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\StorePostFormRequest;
use App\Models\Country;
use App\Models\County;
use App\Models\Jet;
use App\Models\Nationality;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JetController extends Controller
{
    public function create()
    {
        $countries = Country::all();
        $counties = County::all();
        $nationalities = Nationality::all();

        return view('admin.jet.create', compact('countries', 'counties', 'nationalities'));
    }

    public function store(StorePostFormRequest $request)
    {
    	$jet = Jet::createNew(array_merge($request->only([
    		'first_name',
    		'last_name',
            'middle_name',
            // 'date_of_birth',
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
    	]),
            ['date_of_birth' => $request->date_of_birth ? Carbon::createFromFormat('d/m/Y',"{$request->date_of_birth}") : null ]
        ));

    	event(new NewJetCreated($jet));

    	return redirect()->route('admin.jet.show', $jet);
    }

    public function show(Jet $jet)
    {
    	dd($jet);
    }
}
