<?php

namespace App\Models;

use Carbon\Carbon;

class Jockey extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            // $query->whereHas('role', function($q) {
            // 	$q->where('name', 'jockey');
            // });
            $query->where('role_id', 1);
        });
    }

    /*
    	Relationships
    */
   
   	public function coaches()  // could order alphabetically here ->orderBy('last_name', 'desc')
    {
        return $this->belongsToMany(User::class, 'coach_jockey', 'coach_id', 'jockey_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_jockey', 'jockey_id', 'activity_id');
    }

    /*
    	Utilities
     */
    
    public function upcomingActivities()
    {
        return $this->activities()->whereDate('start', '>', Carbon::now())->orderBy('start');
    }

    public function recentActivities()
    {
        return $this->activities()->whereDate('end', '<', Carbon::now())->orderBy('end', 'desc');
    }
}
