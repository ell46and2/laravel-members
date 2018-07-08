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

	public function getFormattedMiscDateAttribute()
	{
		return $this->misc_date->format('d/m/Y');
	}
}
