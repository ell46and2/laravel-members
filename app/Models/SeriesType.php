<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeriesType extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    protected $casts = [
        'total_just_from_place' => 'boolean'
    ];

    /*
    	Relationships
    */
    public function racingExcellences()
    {
    	return $this->hasMany(RacingExcellence::class);
    }

    public function scoring()
    {
    	return $this->hasMany(SeriesScoring::class);
    }

    /*
    	Utilities
    */
    public function pointsForPlace($place)
    {
    	$scoring = $this->scoring()->where('place', $place)->first();

    	if($scoring) {
    		return $scoring->points;
    	}

    	return 0;
    }
}
