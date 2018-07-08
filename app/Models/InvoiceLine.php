<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
	protected $fillable = ['invoice_id', 'invoiceable_id', 'invoiceable_type', 'name', 'value'];

	protected $dates = ['created_at', 'updated_at'];

    public function invoiceable()
	{
		return $this->morphTo();
	}

	public function activity()
	{
		return $this->belongsTo(Activity::class, 'invoiceable_id');
	}

	public function racingExcellence()
	{
		return $this->belongsTo(RacingExcellence::class, 'invoiceable_id');
	}

	/*
		Utilities
	*/


	/*
		Attributes
	*/
	public function getFormattedValueAttribute()
	{
		return '£' . $this->value;
	}

	public function getFormattedValuePerJockeyAttribute()
	{
		return '£' . ($this->value / $this->activity->jockeys->count());
	}
}
