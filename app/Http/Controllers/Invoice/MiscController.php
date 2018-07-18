<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\MiscFormRequest;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function create(Invoice $invoice)
    {
    	$this->authorize('invoice', $invoice);
    	
    	return view('invoice.miscellaneous.create', compact('invoice'));
    }

    public function edit(Invoice $invoice, InvoiceLine $invoiceLine)
    {
    	$this->authorize('invoice', $invoice);

    	return view('invoice.miscellaneous.edit', compact('invoice', 'invoiceLine'));
    }

    public function update(MiscFormRequest $request, Invoice $invoice, InvoiceLine $invoiceLine) // add form request validation
    {
    	$this->authorize('invoice', $invoice);

    	$invoiceLine->update([
            'misc_name' => $request->misc_name,
            'misc_date' => Carbon::createFromFormat('d/m/Y', $request->misc_date),
            'value' => $request->value,
        ]);

        if($invoice->isEditableAndPendingReview()) {
            $invoice->recalculateAndSetTotal();
        }

        return redirect()->route('invoice.show', $invoice);
    }

    public function store(MiscFormRequest $request, Invoice $invoice) // add form request validation
    {
    	$this->authorize('invoice', $invoice);

        InvoiceLine::create([
            'invoice_id' => $invoice->id,
            'misc_name' => $request->misc_name,
            'misc_date' => Carbon::createFromFormat('d/m/Y', $request->misc_date),
            'value' => $request->value,
        ]);

        if($invoice->isEditableAndPendingReview()) {
            $invoice->recalculateAndSetTotal();
        }

        return redirect()->route('invoice.show', $invoice);
    }

    public function destroy(Invoice $invoice, InvoiceLine $invoiceLine)
    {
    	$this->authorize('invoice', $invoice);
    	
        $invoiceLine->delete();

        if($invoice->isEditableAndPendingReview()) {
            $invoice->recalculateAndSetTotal();
        }

        return redirect()->route('invoice.show', $invoice);
    }
}
