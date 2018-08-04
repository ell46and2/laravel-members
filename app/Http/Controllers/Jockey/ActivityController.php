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
		$jockey = Jockey::find($this->currentUser->id);

		$activityTypes = ActivityType::get();

		$events = (new Collection($jockey->events($request)))->paginate(15);

		$coaches = $this->getAllCoachesWorkedWithJockey($jockey);

		return view('jockey.activitylist.index', compact('events', 'coaches', 'activityTypes'));
	}

    // public function show(Activity $activity)
    // {
    // 	// NOTE: mark activity comments for current user as read using vue/javascript,
    // 	// so we can highlight new comments to the user, and also set them as read for the next time
    // 	// they view the 'show' page.

    // 	return view('jockey.activity.show', compact('activity'));
    // }

    private function getAllCoachesWorkedWithJockey(Jockey $jockey)
    {
    	$ownCoachesIds = $jockey->coaches->pluck('id');

    	// incase they have been reassigned
    	$activityCoachesIds = $jockey->activities->pluck('coach_id')->unique();

		$racingExcelCoachesIds = $jockey->racingExcellences->pluck('coach_id')->unique();

		return Coach::find(($ownCoachesIds->merge($activityCoachesIds))->merge($racingExcelCoachesIds))->sortBy('first_name');
    }
}
