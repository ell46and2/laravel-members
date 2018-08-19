<?php

namespace App\Http\Controllers\Jet;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\County;
use App\Models\CrmJockey;
use App\Models\Jockey;
use App\Models\Nationality;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CrmJockeyController extends Controller
{
    public function jockeyCreate()
    {
    	// Jets only
    	
    	$countries = Country::all();
        $counties = County::all();
        $nationalities = Nationality::all();
    	
    	return view('jet.crm.jockey-create', compact('countries', 'counties', 'nationalities'));
    }

    public function jockeyStore(Request $request) // add validation
    {
    	$crmJockey = CrmJockey::create([
    		'first_name' => $request->first_name,
    		'last_name' => $request->last_name,
    		'date_of_birth' => Carbon::createFromFormat('d/m/Y', $request->date_of_birth),
    		'gender' => $request->gender,
    		'county_id' => $request->county_id,
    		'country_id' => $request->country_id,
    		'nationality_id' => $request->nationality_id,
    		'postcode' => $request->postcode,
    		'email' => $request->email,
    		'api_id' => $request->api_id
    	]);

    	return redirect()->route('jets.crm.crm-jockey-show', $crmJockey);
    }

    public function jockeyShow(Jockey $jockey)
    {
    	$crmRecords = $jockey->crmRecords()->paginate(config('jcp.site.pagination'));
        
        return view('jet.crm.jockey-show', compact('jockey', 'crmRecords'));
    }

    public function crmJockeyShow(CrmJockey $crmJockey)
    {
    	// Jets only
        $jockey = $crmJockey; 
        
        $crmRecords = $jockey->crmRecords()->paginate(config('jcp.site.pagination'));
        
        return view('jet.crm.jockey-show', compact('jockey', 'crmRecords'));
    }
}
