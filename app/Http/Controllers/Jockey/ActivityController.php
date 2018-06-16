<?php

namespace App\Http\Controllers\Jockey;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Coach;
use App\Models\Jockey;
use App\Utilities\Collection;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
	public function index(Request $request)
	{
		// dd($request->coach);

		$jockey = Jockey::find(auth()->user()->id);

		$activityTypes = ActivityType::get();

		$events = (new Collection($jockey->events($request)))->paginate(10);

		$coaches = $this->getAllCoachesWorkedWithJockey($jockey);

		return view('jockey.activitylist.index', compact('events', 'coaches', 'activityTypes'));
	}

    public function show(Activity $activity)
    {

    	return view('jockey.activity.show', compact('activity'));
    }

    private function getAllCoachesWorkedWithJockey(Jockey $jockey)
    {
    	$ownCoachesIds = $jockey->coaches->pluck('id');

		$racingExcelCoachesIds = $jockey->racingExcellences->pluck('coach_id');

		return Coach::find($ownCoachesIds->merge($racingExcelCoachesIds));
    }
}
