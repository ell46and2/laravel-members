<?php

namespace App\Http\Controllers\Jet;

use App\Http\Controllers\Controller;
use App\Models\Jet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class TokenAccessController extends Controller
{
    public function index()
    {
    	$jet = Jet::ByAccessToken(request()->email, request()->token)->first();
    	
    	if(!$jet) {
    		return redirect()->route('login');
    	}

        return view('jet.token-access.index', ['token' => request()->token, 'email' => request()->email]);

    	// return redirect()->route('jet.password.edit');
    }

    public function update(Request $request) // validate email, token, password, confirmed
    {   
        $jet = Jet::ByAccessToken($request->email, $request->token)->firstOrFail();

        $jet->update([
            'password' => Hash::make($request->password),
            'access_token' => null
        ]);

        auth()->loginUsingId($jet->id);

        return redirect()->route('jet.dashboard.index');

        // flash success message      
    }
}
