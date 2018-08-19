<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserSelectResource;
use App\Models\Coach;
use App\Models\Jockey;
use Illuminate\Http\Request;

class JockeyController extends Controller
{
    public function index()
    {
    	$coach = Coach::with([
    		'jockeys',
    		'jockeys.coaches',
    	])
    	->findOrFail($this->currentUser->id);

    	$jockeys = $coach->jockeys()->paginate(10);

    	$jockeysResource = UserSelectResource::collection(Jockey::active()->with('role')->get());

    	return view('coach.jockey.index', compact('coach', 'jockeys', 'jockeysResource'));
    }
}
