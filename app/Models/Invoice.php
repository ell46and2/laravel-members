<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	protected $fillable = ['coach_id', 'submitted', 'status', 'total', 'label'];

	protected $dates = ['created_at', 'updated_at'];

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

    /*
        Utilities
    */
    public function isOpen()
    {
        return $this->status === 'pending submission';
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
}
