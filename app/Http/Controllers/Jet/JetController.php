<?php

namespace App\Http\Controllers\Jet;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\County;
use App\Models\Jet;
use App\Models\Nationality;
use Illuminate\Http\Request;

class JetController extends Controller
{
    public function show(Jet $jet)
    {
    	if($this->currentUser->isAdmin() || $this->currentUser->id === $jet->id) {
    		$countries = Country::all();
        	$counties = County::all();
        	$nationalities = Nationality::all();

    	   return view('jet.show', compact('jet', 'countries', 'counties', 'nationalities'));
    	}

        return view('jet.show', compact('jet'));
    }

    public function updateProfile(Request $request, Jet $jet)
    {
    	$jet->update($request->only([
    		'first_name',
    		'last_name',
            'middle_name',
            'address_1',
            'address_2',
            'county_id',
            'country_id',
            'postcode',
            'telephone',
            'twitter_handle',
            'email',
    	]));

    	session()->flash('success', "Jet profile updated");

    	return back();
    }

    public function updateStatus(Request $request, Jet $jet)
    {
    	$jet->update([
            'status' => $request->status
        ]);

        if($request->status === 'deleted') {
            return redirect('admin.dashboard.index');
        }

        session()->flash('success', "Jet status updated to {$request->status}.");

        return back();
    }
}
