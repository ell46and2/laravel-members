<?php

namespace App\Observers;

use App\Models\Activity;

class ActivityObserver
{
	public function creating(Activity $activity)
  	{
  		if($activity->duration) {
    		$activity->end = $activity->start->addMinutes($activity->duration);
    	}
  	}
}
