<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Coach;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\RacingExcellence;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class InvoiceController extends Controller
{
    public function index(Coach $coach)
    {
        $invoices = $coach->invoices()
            ->orderByRaw('submitted IS NULL DESC')
            ->orderBy('submitted', 'desc')
            ->paginate(config('jcp.site.pagination'));

        return view('invoice.index', compact('invoices', 'coach'));
    }

    public function store(Coach $coach)
    {
        $invoice = $this->getLatestOrCreateInvoice($coach);

        return redirect()->route('invoice.show', $invoice);
    }

    public function show(Invoice $invoice)
    {

    	$this->authorize('invoice', $invoice);

        $coach = $invoice->coach->load('jockeys');

        $invoice->load([
            'activityLines', 
            'activityLines.activity', 
            'activityLines.activity.type', 
            'activityLines.activity.jockeys',
            'activityLines.activity.location',
            'racingExcellenceLines',
            'racingExcellenceLines.racingExcellence',
            'racingExcellenceLines.racingExcellence.divisions',
            'racingExcellenceLines.racingExcellence.location',
            'miscellaneousLines',
            'invoiceMileage',
            'invoiceMileage.mileages'
        ]);

        return view('invoice.show', compact('invoice', 'coach'));
    }

    public function submit(Invoice $invoice)
    {
    	$this->authorize('invoice', $invoice);
        // check has at least one invoiceline
        // check date is beetween 1st and 10th
        if(!withinInvoicingPeriod(now()->day)) {
            // throw exception
        }

        // NOTE: notify Admin
        
        $invoice->submitForReview();

        return redirect()->route('invoice.show', $invoice);
    }

    public function approve(Invoice $invoice)
    {
        $this->authorize('invoiceApprove', $invoice);

        $invoice->approve();

        $invoice->coach->addInvoiceMilesToYearlyMileage($invoice);

        // NOTE: notify coach

        return redirect()->route('invoice.show', $invoice);
    }

    public function add(Invoice $invoice)
    {
    	$this->authorize('invoice', $invoice);

        $coach = $invoice->coach->load('jockeys');

        $invoiceables = $coach->invoiceableList();

        return view('invoice.lines.add', compact('invoice', 'coach', 'invoiceables'));
    }

    public function addLines(Request $request, Invoice $invoice)
    {
    	$this->authorize('invoice', $invoice);
        // validate that invoiceable:
        //  - belongs to coach
        //  - is not already an invoiceLine
        //  - start_date is in the past

    	$this->addActivityLines($invoice);
        $this->addRacingExcellenceLines($invoice);

        if($invoice->isEditableAndPendingReview()) {
            $invoice->recalculateAndSetTotal();
        }
    	
        return redirect()->route('invoice.show', $invoice);
    }

    public function removeLine(Invoice $invoice, InvoiceLine $invoiceLine)
    {
    	$this->authorize('invoice', $invoice);

        $invoiceLine->delete();

        if($invoice->isEditableAndPendingReview()) {
            $invoice->recalculateAndSetTotal();
        }

        return redirect()->route('invoice.show', $invoice);
    }

    public function editLine(Invoice $invoice, InvoiceLine $invoiceLine)
    {
        // check line is an activity and user is admin and invoice is not approved
        
        $invoiceLine->load('activity', 'activity.type', 'activity.jockeys');

        return view('invoice.lines.edit', compact('invoice', 'invoiceLine'));
    }

    public function updateLine(Invoice $invoice, InvoiceLine $invoiceLine)
    {
        $this->authorize('edit', $invoiceLine);
        
        $invoiceLine->update(request()->only('value'));

        $invoice->recalculateAndSetTotal();

        return redirect()->route('invoice.show', $invoice);
    }

    public function pdf(Invoice $invoice)
    {
        $this->authorize('invoice', $invoice);

        $coach = $invoice->coach->load('jockeys');

        $invoice->load([
            'activityLines', 
            'activityLines.activity', 
            'activityLines.activity.type', 
            'activityLines.activity.jockeys',
            'activityLines.activity.location',
            'racingExcellenceLines',
            'racingExcellenceLines.racingExcellence',
            'racingExcellenceLines.racingExcellence.divisions',
            'racingExcellenceLines.racingExcellence.location',
            'miscellaneousLines',
            'invoiceMileage',
            'invoiceMileage.mileages'
        ]);

        // NOTE: pdf - can't use flex.

        $layout = view('invoice.pdf', compact('invoice', 'coach'));
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($layout->render());

        return $pdf->stream();

        return $pdf->download("invoice_{$invoice->label}_{$coach->first_name}_{$coach->last_name}.pdf");
    }

    private function addActivityLines(Invoice $invoice)
    {
        if(request()->activities) {
           foreach (request()->activities as $activityId) {
                $activity = Activity::find($activityId);
                $activity->invoiceLine()->create([
                    'invoice_id' => $invoice->id,
                ]);
            } 
        }   	
    }

    private function addRacingExcellenceLines(Invoice $invoice)
    {
        if(request()->racingexcellences) {
            foreach (request()->racingexcellences as $raceExcelId) {
                $raceExcel = RacingExcellence::find($raceExcelId);
                $raceExcel->invoiceLine()->create([
                    'invoice_id' => $invoice->id,
                ]);
            }
        }      
    }

    private function getLatestOrCreateInvoice(Coach $coach)
    {
        if($invoice = $coach->latestOpenInvoice) {
            return $invoice;
        }

        $invoice = $coach->invoices()->create();

        $invoice->invoiceMileage()->create();

        return $invoice;
    }
}
