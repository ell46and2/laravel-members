<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;

class TokenAccessController extends Controller
{
    public function index() // add validation
    {
    	$coach = Coach::ByAccessToken(request()->email, request()->token)->first();
    	
    	if(!$coach) {
    		return redirect()->route('login');
    	}

    	auth()->loginUsingId($coach->id);

    	return redirect()->route('coach.password.edit');
    }
}
