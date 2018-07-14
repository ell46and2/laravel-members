<?php

namespace App\Observers;

use App\Models\InvoiceLine;

class InvoiceLineObserver
{
	public function creating(InvoiceLine $invoiceLine)
  	{
  		if($type = $invoiceLine->invoiceable_type) {
    		//types activity, racingExcellence, mileage -
        
        switch ($type) {
          case "activity":
            $invoiceLine->value = $this->getActivityValue($invoiceLine);
            break;
          case "racing-excellence":
            $invoiceLine->value = $this->getRacingExcellenceValue($invoiceLine);
            break;
          case "mileage":
            // Add code
            break;
        }

    	}
  	}

    private function getActivityValue(InvoiceLine $invoiceLine)
    {
      // NOTE: need to add group activity logic

      $activity = $invoiceLine->invoiceable;

      $rate;

      switch (true) {
        case $activity->jockeys()->count() === 1:
          $rate = config('jcp.activity.rate');
          break;
        case $activity->jockeys()->count() === 2: 
          $rate = config('jcp.group_activity.2');
          break;
        case $activity->jockeys()->count() >= 3:
          $rate = config('jcp.group_activity.3+');
          break;
      }

      return (($activity->duration / 60)) * $rate;
    }

    private function getRacingExcellenceValue(InvoiceLine $invoiceLine)
    {
      $racingExcellence = $invoiceLine->invoiceable;
      $setFee = config('jcp.racing_excellence.set_fee');
      $additionalDivisions = ($racingExcellence->divisions->count() -1) * config('jcp.racing_excellence.additional_divisions');
      
      return $setFee + $additionalDivisions;
    }

  	// public function updating(Activity $activity)
  	// {
  	// 	if($activity->duration) {
  	// 		$activity->end = $activity->start->addMinutes($activity->duration);
  	// 	} else {
  	// 		$activity->end = null; // If activity is blank then set end to null. Double check this.
  	// 	}
  	// }
}
