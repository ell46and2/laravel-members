<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\TokenAccessPasswordSetRequest;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenAccessController extends Controller
{
    public function index()
    {
    	$coach = Coach::ByAccessToken(request()->email, request()->token)->first();
    	
    	if(!$coach) {
    		return redirect()->route('login');
    	}

        return view('coach.token-access.index', ['token' => request()->token, 'email' => request()->email]);

    	// return redirect()->route('coach.password.edit');
    }

    public function update(TokenAccessPasswordSetRequest $request) // validate email, token, password, confirmed
    {   
        $user = Coach::ByAccessToken($request->email, $request->token)->firstOrFail();

        $coach->update([
            'password' => Hash::make($request->password),
            'access_token' => null
        ]);

        auth()->loginUsingId($coach->id);

        return redirect()->route('coach.dashboard.index');

        // flash success message      
    }
}
