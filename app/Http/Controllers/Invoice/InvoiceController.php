<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Coach;
use App\Models\Invoice;
use App\Models\RacingExcellence;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function addLines(Request $request, Coach $coach)
    {
        // validate that invoiceable:
        //  - belongs to coach
        //  - is not already an invoiceLine
        //  - start_date is in the past
        
        // Policy: $coach is the current user or user is an admin.

    	$invoice = $this->getLatestInvoice($coach);

    	$this->addActivityLines($invoice);
        $this->addRacingExcellenceLines($invoice);
    	
    }

    private function addActivityLines(Invoice $invoice)
    {
    	foreach (request()->activities as $activityId) {
    		$activity = Activity::find($activityId);
    		$activity->invoiceLine()->create([
	            'invoice_id' => $invoice->id,
	        ]);
    	}
    }

    private function addRacingExcellenceLines(Invoice $invoice)
    {
        foreach (request()->racingexcellences as $raceExcelId) {
            $raceExcel = RacingExcellence::find($raceExcelId);
            $raceExcel->invoiceLine()->create([
                'invoice_id' => $invoice->id,
            ]);
        }
    }

    private function getLatestInvoice(Coach $coach)
    {
    	if($invoice = $coach->latestOpenInvoice) {
    		return $invoice;
    	}

    	return $coach->invoices()->create();
    }
}
