<?php

namespace App\Observers;

use App\Models\Activity;

class ActivityObserver
{
	public function creating(Activity $activity)
  	{
    	$activity->end = $activity->start->addMinutes($activity->duration);
  	}
}
