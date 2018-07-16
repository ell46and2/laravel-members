<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachMileage extends Model
{
    protected $fillable = ['coach_id', 'year', 'miles'];

    public function coach()
    {
    	return $this->belongsTo(Coach::class, 'coach_id');
    }
}
