<?php

namespace App\Observers;

use App\Models\Coach;
use App\Models\Mileage;

class InvoiceMileageObserver
{
	public function created(Mileage $mileage)
	{
		// calculate overall invoiceMileage total from all attached mileage
    $this->calculateMileageValue($mileage); 
	}

  public function updated(Mileage $mileage)
  {
    // calculate overall invoiceMileage total from all attached mileage
  }

  public function deleted(Mileage $mileage)
  {
    // calculate overall invoiceMileage total from all attached mileage
  }

  private function calculateMileageValue(Mileage $mileage) {

      $invoiceMileage = $mileage->invoiceMileage;
      $coach = $invoiceMileage->invoice->coach;

      $currentMileageForCurrentYear = optional($coach->currentMileage)->miles;

      // if no $coach->currentMileage then create new CoachMileage and set the miles to 0.
      if($currentMileageForCurrentYear === null) {
        $currentMileageForCurrentYear = $this->createNewCoachMileageForCurrentYear($coach);
      }


      $currentYearInvoiceMileage = $invoiceMileage->mileages()->where('date', '>=', now()->startOfYear())->get();
      $currentYearInvoiceMiles = $currentYearInvoiceMileage->sum('miles');
      $totalForCurrentYear = $currentMileageForCurrentYear + $currentYearInvoiceMiles;
      $valueForCurrentYear = $this->getMileageValue($totalForCurrentYear, $currentYearInvoiceMiles);

      // dd($valueForCurrentYear);
      // if $currentYearInvoiceMiles->count() === the count of all invoice mileage - then all mileage is for the current year
      //  so just return $valueForCurrentYear
      if($currentYearInvoiceMileage->count() === $invoiceMileage->mileages()->count()) {
        $invoiceMileage->update([
          'value' => number_format($valueForCurrentYear, 2)
        ]);

        return;
      }

      // we have some mileage from the previos year, so calculate that from coachs mileage from last year.
      $mileageForLastYear = optional($coach->lastYearsMileage)->miles;
      $previousYearInvoiceMiles = $invoiceMileage->mileages()->where('date', '<', now()->startOfYear())->get();
      $totalForPreviousYear = $mileageForLastYear + $previousYearInvoiceMiles;
      $valueForLastYear = getMileageValue($totalForPreviousYear, $previousYearInvoiceMiles);

      $invoiceMileage->update([
        'value' => number_format(($valueForCurrentYear + $valueForLastYear), 2)
      ]);

      /*
      

      // Get all mileage claims that are for the current year
        $currentYearInvoiceMiles = $invoiceMileage->mileage->whereYear('date', '=', $currentYear);
        $totalForCurrentYear = $currentMileageForCurrentYear + $currentYearInvoiceMiles;
        
        $valueForCurrentYear = getMileageValue($totalForCurrentYear, $currentYearInvoiceMiles);


      // if $currentYearInvoiceMiles->count() === the count of all invoice mileage - then all mileage is for the current year
      //  so just return $valueForCurrentYear
      

      // else we have some mileage from last year
        $lastYear = $currentYear - 1;
        $mileageForLastYear = $coach->mileageForYear($mileageForLastYear);
        $previousYearInvoiceMiles = $invoiceMileage->mileage->whereYear('date', '=', $lastYear);
        $totalForPreviousYear = $mileageForLastYear + $previousYearInvoiceMiles;

        $valueForLastYear = getMileageValue($totalForPreviousYear, $previousYearInvoiceMiles);

        return $valueForCurrentYear + $valueForLastYear;
        */
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
