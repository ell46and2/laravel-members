<?php

namespace App\Http\Controllers\Admin;


use App\Events\Admin\RacingExcellence\NewRacingExcellenceCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\RacingExcellence\StorePostFormRequest;
use App\Http\Requests\RacingExcellence\UpdatePutFormRequest;
use App\Http\Resources\RacingExcellence\RaceResource;
use App\Http\Resources\UserSearchResource;
use App\Jobs\RacingExcellence\NotifyAllAmendedRacingExcellence;
use App\Jobs\RacingExcellence\NotifyCoachAddedToRacingExcellence;
use App\Jobs\RacingExcellence\NotifyCoachRemovalRacingExcellence;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingLocation;
use App\Models\SeriesType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RacingExcellenceController extends Controller
{
	public function create()
	{
		// add a scope to only get those who are valid to race.
		$jockeysResource = UserSearchResource::collection(Jockey::all()->sortBy('full_name'));

		$coaches = Coach::take(20)->get(); // NOTE: change to just Coach::all();

		$locations = RacingLocation::all();

		$seriesTypes = SeriesType::all();

		return view('admin.racing-excellence.create', compact('jockeysResource', 'coaches', 'locations', 'seriesTypes'));
	}

    public function store(StorePostFormRequest $request) //StorePostFormRequest
    {
    	// NOTE: add form request - validate that participant if number exists as jockey and if not is a string(name)

    	// dd($request->divisions);

    	$racingExcellence = RacingExcellence::createRace($request);

    	event(new NewRacingExcellenceCreated($racingExcellence));

    	// NOTE: do we show admin the results page /racing-excellence/id/results ?
        return redirect()->route('racing-excellence.results.create', $racingExcellence); 
  		// return redirect()->route('admin.racing-excellence.show', $racingExcellence);
    }

    public function show()
    {
        // NOTE: Do we need or just show the results page?

    	return 'test';
    }

    public function edit(RacingExcellence $racingExcellence)
    {
    	$jockeysResource = UserSearchResource::collection(Jockey::all()->sortBy('full_name'));
    	$raceResource = new RaceResource($racingExcellence);
        $coaches = Coach::take(20)->get(); // NOTE: change to just Coach::all();
        $locations = RacingLocation::all();
        $seriesTypes = SeriesType::all();

        // if current user is the assigned coach show edit view with just divisions to edit

    	return view('admin.racing-excellence.edit', 
            compact('raceResource', 'jockeysResource', 'racingExcellence', 'coaches', 'locations', 'seriesTypes')
        );
    }

    public function update(UpdatePutFormRequest $request, RacingExcellence $racingExcellence) // add form request
    {
        // check that there are some participants, if none send an error back.

        // check if coach has changed, if so notify old coach and new coach
        $oldCoachId = null;

        if($racingExcellence->coach_id != $request->coach_id) {
            $oldCoachId = $racingExcellence->coach_id;
        }

        $racingExcellence->update([
            'coach_id' => $request->coach_id,
            'location_id' => $request->location_id,
            'series_id' => $request->series_id,
            'start' => Carbon::createFromFormat('d/m/Y H:i',"{$request->start_date} {$request->start_time}"),
        ]);

        if($oldCoachId) {
            $this->dispatch(new NotifyCoachRemovalRacingExcellence($racingExcellence, $oldCoachId));
            $this->dispatch(new NotifyCoachAddedToRacingExcellence($racingExcellence));
            // notify old coach of removal
            // notify new coach
        }

        // notify jockeys and coach of racing excellence being updated/amended.
        $this->dispatch(new NotifyAllAmendedRacingExcellence($racingExcellence));
        
        return redirect()->route('racing-excellence.results.create', $racingExcellence);
    }
}
