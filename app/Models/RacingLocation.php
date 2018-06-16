<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacingLocation extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function racingExcellences()
    {
    	return $this->hasMany(RacingExcellence::class);
    }
}
