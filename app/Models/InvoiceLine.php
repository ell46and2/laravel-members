<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
	protected $fillable = ['invoice_id', 'invoiceable_id', 'invoiceable_type', 'misc_name', 'misc_date','value'];

	protected $dates = ['created_at', 'updated_at', 'misc_date'];

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

	public function invoice()
	{
		return $this->belongsTo(Invoice::class, 'invoice_id');
	}

	/*
		Utilities
	*/


	/*
		Attributes
	*/
	public function getFormattedValueAttribute()
	{
		return '£' . toTwoDecimals($this->value);
	}

	public function getFormattedValuePerJockeyAttribute()
	{
		return '£' . toTwoDecimals($this->value / $this->activity->jockeys->count());
	}

	public function getFormattedMiscDateAttribute()
	{
		return $this->misc_date->format('l jS F');
	}
}
