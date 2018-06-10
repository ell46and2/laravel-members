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

    public function competencyAsssessments()
    {
        return $this->hasMany(CompetencyAsssessment::class, 'coach_id');
    }

    public function racingExcellenceDivisions()
    {
           return $this->belongsToMany(RacingExcellenceDivision::class, 'racing_excellence_participants', 'jockey_id', 'division_id');
    }

    // public function racingExcellences()
    // {
    //        return $this->racingExcellenceDivisions->racingExellence();
    // }

    public function racingExcellences()
    {
        return $this->hasManyThrough(
            RacingExcellence::class, 
            RacingExcellenceParticipant::class,
            'jockey_id',
            'id',
            'id',
            'id'
        );
    }

    /*
    	Utilities
     */
    
    public function upcomingEvents() // activities and Racing Excellence
    {
        $activities = collect($this->activities()->get());
        $racingExcellences = collect($this->racingExcellences()->get());
        return $activities->merge($racingExcellences)->sortBy('start');
    }
    
    public function upcomingActivities()
    {
        return $this->activities()->whereDate('start', '>', Carbon::now())->orderBy('start');
    }

    public function recentActivities()
    {
        return $this->activities()->whereDate('end', '<', Carbon::now())->orderBy('end', 'desc');
    }

    public function trainingTimeThisMonth()
    {   
        $startOfMonth = Carbon::now()->startOfMonth();

        // dd($startOfMonth);

        return $this->activities()
            ->whereBetween('end', [$startOfMonth, Carbon::now()])
            ->sum('duration') / 60;

        // get activities for this month that have the jockey in.
        // sum the duration and divide by 60 to get in hours..
    }

    public function trainingTimeThisMonthPercentage()
    {
        return ($this->trainingTimeThisMonth() / 4) * 100; // to nearest whole number?
    }
}
