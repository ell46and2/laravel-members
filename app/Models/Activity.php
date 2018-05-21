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
       	return $this->belongsToMany(User::class, 'activity_jockey', 'activity_id', 'jockey_id');
    }

	public function coach()
	{
		return $this->belongsTo(User::class, 'coach_id');
	}

	public function notifications()
    {
    	return $this->morphMany(Notification::class, 'notifiable');
    }
    

    /*
    	Utilities
    */
    public function addJockey(User $jockey)
    {
    	if(!$this->jockeys()->where('jockey_id', $jockey->id)->count()) {
    		$this->jockeys()->attach($jockey);
    	}	
    }

    public function removeJockey(User $jockey)
    {
    	$this->jockeys()->detach($jockey);
    }

    public function addJockeysById(Array $jockeyIds)
    {
    	// look at replacing with sync() 
    	// As sync() will remove any jockeys that are not in the array.
    	$this->jockeys()->attach($jockeyIds); 
    }
}
