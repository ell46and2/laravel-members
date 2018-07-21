<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\MileageFormRequest;
use App\Models\Invoice;
use App\Models\Mileage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MileageController extends Controller
{
    public function create(Invoice $invoice)
    {
        $this->authorize('invoice', $invoice);

        return view('invoice.mileage.create', compact('invoice'));
    }

    public function edit(Invoice $invoice, Mileage $mileage)
    {
        $this->authorize('invoice', $invoice);

        
        return view('invoice.mileage.edit', compact('invoice', 'mileage'));
    }

    public function update(MileageFormRequest $request, Invoice $invoice, Mileage $mileage) // add validation
    {
        $this->authorize('invoice', $invoice);

        $mileage->update([
            'description' => $request->description,
            'mileage_date' => Carbon::createFromFormat('d/m/Y', $request->mileage_date),
            'miles' => $request->miles
        ]);

        if($invoice->isEditableAndPendingReview()) {
            $invoice->recalculateAndSetTotal();
        }
        
        return redirect("/invoices/invoice/{$invoice->id}?#mileage");
    }

    public function store(MileageFormRequest $request, Invoice $invoice) // add validation
    {
    	$this->authorize('invoice', $invoice);

    	$invoice->invoiceMileage->mileages()->create([
    		'description' => $request->description,
    		'mileage_date' => Carbon::createFromFormat('d/m/Y', $request->mileage_date),
    		'miles' => $request->miles
    	]);

        if($invoice->isEditableAndPendingReview()) {
            $invoice->recalculateAndSetTotal();
        }

    	return redirect("/invoices/invoice/{$invoice->id}?#mileage");
    }

    public function destroy(Invoice $invoice, Mileage $mileage)
    {
        $this->authorize('invoice', $invoice);
        
        // check mileage belongs to invoice - throw exception if it doesn't
        if(!$mileage->belongsToInvoice($invoice)) {
            // throw exception error
            return;
        }
        
        $mileage->delete();

        if($invoice->isEditableAndPendingReview()) {
            $invoice->recalculateAndSetTotal();
        }

        return redirect("/invoices/invoice/{$invoice->id}?#mileage");
    }
}
