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
    public function index(Coach $coach)
    {
        // Policy: $coach is the current user or user is an admin.

        $invoices = $coach->invoices; // paginate?

        return view('invoice.index', compact('invoices', 'coach'));
    }

    public function store(Coach $coach)
    {
        $invoice = $this->getLatestOrCreateInvoice($coach);

        return redirect()->route('invoice.show', $invoice);
    }

    public function show(Invoice $invoice)
    {
        dd($invoice);
    }

    public function addLines(Request $request, Invoice $invoice)
    {
        // validate that invoiceable:
        //  - belongs to coach
        //  - is not already an invoiceLine
        //  - start_date is in the past
        
        // Policy: $coach is the current user or user is an admin.

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

    private function getLatestOrCreateInvoice(Coach $coach)
    {
        if($invoice = $coach->latestOpenInvoice) {
            return $invoice;
        }

        return $coach->invoices()->create();
    }
}
