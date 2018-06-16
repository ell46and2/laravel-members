<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
	public function edit() 
	{
		return view('user.password.edit');
	}

    public function update(UpdatePasswordFormRequest $request) // Add form Request
    {
    	$user = auth()->user();

    	$user->update([
    		'password' => Hash::make($request->password)
    	]);

    	// need to determine user role to get route to return to.
    	return redirect()->route('jockey.profile.index');
    }
}
