<?php

namespace App\Observers;

use App\Models\RacingExcellenceParticipant;

class RacingExcellenceParticipantObserver
{
	public function updating(RacingExcellenceParticipant $participant)
  	{
  		// if place set or race_completed = false
  		// calculate total_points
  		if($participant->place !== null || !$participant->completed_race) {
  			$participant->total_points = $participant->calculateTotalPoints();
  		}
  	}
}
