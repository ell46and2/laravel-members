<?php

namespace App\Http\Controllers\Admin;


use App\Events\Admin\RacingExcellence\NewRacingExcellenceCreated;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserSearchResource;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use Illuminate\Http\Request;

class RacingExcellenceController extends Controller
{
	public function create()
	{
		// add a scope to only get those who are valid to race.
		$jockeysResource = UserSearchResource::collection(Jockey::all()->sortBy('full_name'));

		return view('admin.racing-excellence.create', compact('jockeysResource'));
	}

    public function store(Request $request)
    {
    	$racingExcellence = RacingExcellence::createRace($request);

    	event(new NewRacingExcellenceCreated($racingExcellence));

  		return redirect()->route('admin.racing-excellence.show', $racingExcellence);
    }
}
