<?php

namespace App\Http\Controllers\Jockey;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
    	$jockey = auth()->user();

    	return view('jockey.profile.index', compact('jockey'));
    }

    public function edit()
    {
    	$jockey = auth()->user();

    	return view('jockey.profile.edit', compact('jockey'));
    }

    public function update()
    {
    	$jockey = auth()->user();

    	$jockey->update(request()->only([
    		'first_name',
    		'last_name', 
    		'telephone', 
    		'street_address', 
    		'city', 
    		'postcode', 
    		'email'
    	]));

    	return redirect()->route('jockey.profile.index');
    }
}
