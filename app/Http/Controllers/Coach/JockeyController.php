<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;

class JockeyController extends Controller
{
    public function index()
    {
    	$coach = Coach::with([
    		'jockeys',
    		'jockeys.coaches',
    	])
    	->findOrFail(auth()->user()->id);

    	return view('coach.jockey.index', compact('coach'));
    }
}
