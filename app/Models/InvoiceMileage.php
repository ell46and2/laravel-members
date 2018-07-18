<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceMileage extends Model
{
	protected $fillable = ['value'];

	protected $dates = ['created_at', 'updated_at'];

    public function invoice()
    {
    	return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function mileages()
    {
    	return $this->hasMany(Mileage::class, 'invoice_mileage_id')->orderBy('mileage_date');
    }
}
