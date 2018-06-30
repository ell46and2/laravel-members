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

  	public function updating(Activity $activity)
  	{
  		if($activity->duration) {
  			$activity->end = $activity->start->addMinutes($activity->duration);
  		} else {
  			$activity->end = null; // If activity is blank then set end to null. Double check this.
  		}
  	}
}
