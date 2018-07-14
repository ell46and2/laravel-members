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
        return $this->lines()->whereInvoiceableType(null);
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
            'status' => 'pending review',
            'label' => Carbon::now()->subMonths(1)->format('F Y'),
            'total' => $this->calculateOverallValue()
        ]);
    }

    public function calculateOverallValue()
    {
        return $this->lines()->sum('value');
    }

    public function getOverallValueAttribute()
    {
        if($this->total) {
            return number_format($this->total, 2, '.', ',');
        }

        return number_format($this->calculateOverallValue(), 2, '.', ',');
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

    public function getSubmittedDateAttribute()
    {
        return $this->submitted->format('l jS F');
    }
}
