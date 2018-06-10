<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RacingExcellence extends Model
{ 
	protected $fillable = ['coach_id', 'start', 'location'];

	/*
		Relationships
	*/
    public function participants()
    {
        return $this->hasManyThrough(
        	RacingExcellenceParticipant::class, 
        	RacingExcellenceDivision::class,
        	'racing_excellence_id',
        	'division_id',
        	'id',
        	'id'
        );
    }

    public function jockeys()
    {
    	return $this->participants()->whereNotNull('jockey_id');
    }

    public function coach()
	{
		return $this->belongsTo(Coach::class, 'coach_id');
	}

	public function divisions()
	{
		return $this->hasMany(RacingExcellenceDivision::class);
	}

	public function notifications()
    {
    	return $this->morphMany(Notification::class, 'notifiable');
    }

    
    /*
    	Utilities
    */
	public static function createRace(Request $request)
	{
		$racingExcellence = self::create($request->only(['coach_id', 'start', 'location']));
		$racingExcellence->addDivisions(collect(request()->only(['divisions'])['divisions']));

		return $racingExcellence;
	}

	public function addDivisions(Collection $divisionsData)
	{
		$divisionsData->each(function($divisionData) {		
			$division = $this->divisions()->create();
			$division->addJockeysById(collect($divisionData['jockeys']));
			$division->addExternalParticipants(collect($divisionData['external_participants']));
		});
	}
}
