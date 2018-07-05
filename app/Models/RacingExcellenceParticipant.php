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
	public function placePoints($series = null)
	{
		if(!$series) {
			$series = $this->racingExcellence->series;
		}

		// if completed race - calculate points for place
		if($this->completed_race && $this->place) {
			return $series->pointsForPlace($this->place);
		}

		return 0;
	}

	public function calculateTotalPoints()
	{	
		$series = $this->racingExcellence->series;

		$total = $this->placePoints($series);

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

	public function getAvatar()
	{
		if($this->name) {
			// NOTE: Do we want a different avatar image for external jockeys?
			return config('jcp.buckets.avatars') . 'default_avatar.png';
		}

		return $this->jockey->getAvatar();
	}

	/*
		Attributes
	*/
	public function getFormattedNameAttribute()
	{
		return $this->name ?? $this->jockey->full_name;
	}

	public function getFormattedPlaceAttribute()
	{
		return $this->place === 1000000 ? 
			!$this->completed_race ? 'dnf' : '-'
			: $this->place;
	}
}
