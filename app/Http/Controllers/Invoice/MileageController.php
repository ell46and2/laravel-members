<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MileageController extends Controller
{
    public function store(Request $request, Invoice $invoice)
    {
    	$this->authorize('invoice', $invoice);

    	$invoice->invoiceMileage->mileages()->create([
    		'description' => $request->description,
    		'mileage_date' => Carbon::createFromFormat('d/m/Y', $request->mileage_date),
    		'miles' => $request->miles
    	]);

    	// use observer to calculate overall invoiceMileage value each time mileage is
    	// either, added, updated, deleted

    	return redirect()->route('invoice.show', $invoice);
    }
}
