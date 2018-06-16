<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeriesType extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function racingExcellences()
    {
    	return $this->hasMany(RacingExcellence::class);
    }
}
