<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	protected $fillable = ['coach_id', 'submitted', 'status', 'total', 'label'];

	protected $dates = ['created_at', 'updated_at', 'submitted'];

    public function coach()
    {
    	return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function activityLines()
    {
        return $this->lines()->whereInvoiceableType('activity');
    }

    public function racingExcellenceLines()
    {
        return $this->lines()->whereInvoiceableType('racing-excellence');
    }

    public function miscellaneousLines()
    {
        return $this->lines()->whereInvoiceableType(null)->orderBy('misc_date');
    }

    public function invoiceMileage()
    {
        return $this->hasOne(InvoiceMileage::class, 'invoice_id');
    }

    /*
        Utilities
    */
    public function isOpen()
    {
        return $this->status === 'pending submission';
    }

    public function submitForReview()
    {
        $this->update([
            'submitted' => now(),
            'status' => 'pending review',
            'label' => Carbon::now()->subMonths(1)->format('F Y'),
            'total' => $this->calculateOverallValue()
        ]);
    }

    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'total' => $this->calculateOverallValue()
        ]);
    }

    public function calculateOverallValue()
    {
        return $this->lines()->sum('value') + $this->invoiceMileage->value;
    }

    public function getOverallValueAttribute()
    {
        if($this->total) {
            return $this->total;
        }

        return $this->calculateOverallValue();
    }

    public function getFormattedStatusAttribute()
    {
        switch ($this->status) {
            case 'pending submission': 
                return 'Open';
            case 'pending review':
                return 'Submitted';
            case 'approved':
                return 'Approved';
        }
    }

    public function recalculateAndSetTotal()
    {
        $this->update([
            'total' => $this->calculateOverallValue()
        ]);
    }

    public static function inSubmitInvoicePeriod()
    {
        $day = now()->day;

        if(withinInvoicingPeriod($day)) {
            return true;
        }

        return false;
    }

    public function canBeSubmitted()
    {
        if($this->inSubmitInvoicePeriod() && $this->isOpen()) {
            return true;
        }

        return false;
    }

    public function submittableButOutOfWindow()
    {
        if(!$this->inSubmitInvoicePeriod() && $this->isOpen()) {
            return true;
        }

        return false;
    }

    public function getSubmittedDateAttribute()
    {
        return $this->submitted->format('l jS F');
    }

    public function getSubmittedDateShortAttribute()
    {
        return $this->submitted->format('d/m/Y');
    }

    public function getInvoicePeriodMonthSubmittedAttribute()
    {
        return $this->submitted->subMonth()->format('F');
    }

    public function isEditable()
    {
        if($this->status == 'approved') {
            return false;
        }

        if(auth()->user()->isCoach() && $this->status == 'pending review') {
            return false;
        }

        return true;
    }

    public function isEditableAndPendingReview()
    {
        if($this->status === 'pending review' && auth()->user()->isAdmin()) {
            return true;
        }  

        return false;   
    }
}
