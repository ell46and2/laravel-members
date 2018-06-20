<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RacingExcellenceParticipant extends Model
{
	protected $fillable = [
		'jockey_id',
		'name', 
		'racing_excellence_id',
		'completed_race',
		'place',
        'completed_race',
        'presentation_points',
        'professionalism_points',
        'coursewalk_points',
        'riding_points',
        'total_points',
        'feedback'
	];

	protected $casts = [
        'completed_race' => 'boolean'
    ];

	/*
		Relationships
 	*/
    public function jockey()
	{
		return $this->belongsTo(Jockey::class, 'jockey_id');
	}

	public function division()
	{
		return $this->belongsTo(RacingExcellenceDivision::class, 'division_id');
	}

	public function racingExcellence()
	{
		return $this->belongsTo(RacingExcellence::class, 'racing_excellence_id');
	}

	/*
		Utilities
	*/
	public function calculateTotalPoints()
	{	
		$total = 0;
	
		$series = $this->racingExcellence->series;

		// if completed race - calculate points for place
		if($this->completed_race && $this->place) {
			$total = $total + $series->pointsForPlace($this->place);
		}

		if($series->total_just_from_place) {
			return $total;
		} 
		
		return $total + array_sum([
			$this->presentation_points,
			$this->professionalism_points,
			$this->coursewalk_points,
			$this->riding_points
		]);
	}
}
