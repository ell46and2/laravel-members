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
}
