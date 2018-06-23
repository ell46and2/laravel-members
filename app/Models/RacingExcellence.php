<?php

namespace App\Models;

use App\Filters\RacingExcellence\RacingExcellenceFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RacingExcellence extends Model
{ 
	protected $fillable = ['coach_id', 'start', 'location_id', 'series_id'];

	protected $dates = ['created_at', 'updated_at', 'start'];

    protected $casts = [
        'completed' => 'boolean'
    ];

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

	public function location()
	{
		return $this->belongsTo(RacingLocation::class, 'location_id');
	}

	public function series()
	{
		return $this->belongsTo(SeriesType::class);
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
		$racingExcellence = self::create($request->only([
			'coach_id', 'start', 'location_id', 'series_id'
		]));

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

    public function scopeFilter(Builder $builder, $request)
    {
        return (new RacingExcellenceFilters($request))->filter($builder);
    }

    /*
        Attributes
     */

	public function getFormattedTypeAttribute()
    {
        return 'Racing Excellence';
    }

    public function getFormattedStartAttribute()
    {
        return $this->start->format('l jS F Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start->format('H:i');
    }

    public function getFormattedLocationAttribute()
    {
        return ucfirst($this->location->name);
    }

    public function getFormattedJockeyOrGroupAttribute()
    {
        return 'Group';
    }

    public function getNotificationLinkAttribute()
    {
        // NOTE: need to have different urls depending on the users role.
        return config('app.url') . urlAppendByRole() . "/racing-excellence/{$this->id}";
    }
}
