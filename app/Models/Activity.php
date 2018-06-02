<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $fillable = ['start', 'duration', 'end', 'location'];

	protected $dates = ['created_at', 'updated_at', 'start', 'end'];

	/*
		Relationships
	*/
    public function jockeys()
    {
       	return $this->belongsToMany(Jockey::class, 'activity_jockey', 'activity_id', 'jockey_id');
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
}
