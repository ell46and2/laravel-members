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
    	$this->jockeys()->attach($jockeyIds);
    }
}
