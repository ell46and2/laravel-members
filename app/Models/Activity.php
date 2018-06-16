<?php

namespace App\Models;

use App\Filters\Activity\ActivityFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $fillable = ['activity_type_id', 'start', 'duration', 'end', 'location'];

	protected $dates = ['created_at', 'updated_at', 'start', 'end'];

	/*
		Relationships
	*/
    public function jockeys()
    {
       	return $this->belongsToMany(Jockey::class, 'activity_jockey', 'activity_id', 'jockey_id')
            ->withPivot('feedback');;
    }

	public function coach()
	{
		return $this->belongsTo(Coach::class, 'coach_id');
	}

	public function notifications()
    {
    	return $this->morphMany(Notification::class, 'notifiable');
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function location()
    {
        return $this->belongsTo(ActivityLocation::class, 'location_id');
    }

    /*
    	Utilities
    */
    public function addJockey(Jockey $jockey)
    {
    	if(!$this->jockeys()->where('jockey_id', $jockey->id)->count()) {
    		$this->jockeys()->attach($jockey);
    	}	
    }

    public function removeJockey(Jockey $jockey)
    {
    	$this->jockeys()->detach($jockey);
    }

    public function addJockeysById(Array $jockeyIds)
    {
    	// look at replacing with sync() 
    	// As sync() will remove any jockeys that are not in the array.
    	$this->jockeys()->sync($jockeyIds); 
    }

    public function getFormattedTypeAttribute()
    {
        return ucfirst($this->activityType->name);
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
        if($this->location) {
            return ucfirst($this->location->name);
        }

        return ucfirst($this->location_name);
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new ActivityFilters($request))->filter($builder);
    }
}
