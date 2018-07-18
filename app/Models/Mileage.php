<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mileage extends Model
{
	protected $fillable = ['description', 'mileage_date', 'miles'];

	protected $dates = ['created_at', 'updated_at', 'mileage_date'];

    public function invoiceMileage()
    {
    	return $this->belongsTo(InvoiceMileage::class, 'invoice_mileage_id');
    }

    /*
    	Utilities
     */
    public function belongsToInvoice(Invoice $invoice)
    {
    	return $this->invoiceMileage->invoice_id == $invoice->id;
    }

    /*
        Attributes
     */
    public function getFormattedDateAttribute()
    {
        return $this->mileage_date->format('l jS F');
    }

}
