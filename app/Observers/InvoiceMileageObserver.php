<?php

namespace App\Observers;

use App\Models\Coach;
use App\Models\Mileage;

class InvoiceMileageObserver
{
	public function created(Mileage $mileage)
	{
    $this->calculateMileageValue($mileage); 
	}

  public function updated(Mileage $mileage)
  {
    $this->calculateMileageValue($mileage); 
  }

  public function deleted(Mileage $mileage)
  {
    $this->calculateMileageValue($mileage); 
  }

  private function calculateMileageValue(Mileage $mileage) {
    $invoiceMileage = $mileage->invoiceMileage;
    $coach = $invoiceMileage->invoice->coach;      

    $currentMileageForCurrentYear = optional($coach->currentMileage)->miles;

    // if no $coach->currentMileage then create new CoachMileage and set the miles to 0.
    if($currentMileageForCurrentYear === null) {
      $currentMileageForCurrentYear = $this->createNewCoachMileageForCurrentYear($coach);
    }


    $currentYearInvoiceMileage = $invoiceMileage->mileages()->where('mileage_date', '>=', now()->startOfYear())->get();
    $currentYearInvoiceMiles = $currentYearInvoiceMileage->sum('miles');
    $totalForCurrentYear = $currentMileageForCurrentYear + $currentYearInvoiceMiles;
    $valueForCurrentYear = $this->getMileageValue($totalForCurrentYear, $currentYearInvoiceMiles);

    
    // if $currentYearInvoiceMiles->count() === the count of all invoice mileage - then all mileage is for the current year
    //  so just return $valueForCurrentYear
    if($currentYearInvoiceMileage->count() === $invoiceMileage->mileages()->count()) {
      $invoiceMileage->update([
        'value' => toTwoDecimals($valueForCurrentYear)
      ]);

      return;
    }

    // we have some mileage from the previos year, so calculate that from coachs mileage from last year.
    $mileageForLastYear = optional($coach->lastYearsMileage)->miles;
    $previousYearInvoiceMiles = $invoiceMileage->mileages()->where('mileage_date', '<', now()->startOfYear())->sum('miles');
    $totalForPreviousYear = $mileageForLastYear + $previousYearInvoiceMiles;
    $valueForLastYear = $this->getMileageValue($totalForPreviousYear, $previousYearInvoiceMiles);

    $invoiceMileage->update([
      'value' => toTwoDecimals($valueForCurrentYear + $valueForLastYear)
    ]);
  }

  private function getMileageValue($total, $newMiles) {
    // If total under the threshold
    if($total < config('jcp.mileage.threshold')) {
      return $newMiles * config('jcp.mileage.rate_below_threshold');
    }
    // If current already over threshold
    if(($total - $newMiles) >= config('jcp.mileage.threshold')) {
      return $newMiles * config('jcp.mileage.rate_above_threshold');
    }

    $milesAbove = $total - config('jcp.mileage.threshold'); // get 10000 from jcp.mileage.threshold
    $milesUnder = $newMiles - $milesAbove;

    return ($milesAbove * config('jcp.mileage.rate_above_threshold')) + ($milesUnder * config('jcp.mileage.rate_below_threshold'));
  }

  private function createNewCoachMileageForCurrentYear(Coach $coach)
  {
    $coach->mileages()->create([
      'year' => now()->year,
      'miles' => 0
    ]);

    return 0;
  }
}
