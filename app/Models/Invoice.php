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
}
