<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return $this->belongsToMany(Coach::class, 'coach_jockey', 'coach_id', 'jockey_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_jockey', 'jockey_id', 'activity_id')
            ->withPivot('feedback');
    }

    public function competencyAsssessments()
    {
        return $this->hasMany(CompetencyAssessment::class, 'jockey_id');
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
    
    public function events(Request $request)
    {
        $activities = collect($this->getEventActivities($request));

        $racingExcellences = collect($this->getEventRacingEcellence($request));

        $competencyAsssessments = collect($this->getEventCompetencyAssessments($request));

        $events = ($activities->merge($racingExcellences))
            ->merge($competencyAsssessments);

        if($request->order === 'desc') {
            return $events->sortByDesc('start');
        }

        return $events->sortBy('start');
    }

    public function getEventCompetencyAssessments($request)
    {
        if($request->type === 'ca' || !$request->type) {
            return $this->competencyAsssessments()
                ->with(['coach'])->filter($request)->get();
        }

        return [];
    }

    public function getEventActivities($request)
    {
        if(is_numeric($request->type) || !$request->type) {
            return $this->activities()->with([
                'coach',
                'activityType',
                'location',
            ])
            ->filter($request)
            ->get();
        }

        return [];
    }

    public function getEventRacingEcellence($request)
    {
        if($request->type === 're' || !$request->type) {
            return $this->racingExcellences()->with([
                'coach',
                'location',
            ])
            ->filter($request)
            ->get();
        }

        return [];
    }
    
    public function upcomingEvents() // activities and Racing Excellence
    {
        $activities = collect($this->upcomingActivities()->get());
        $racingExcellences = collect($this->racingExcellences()->whereDate('start', '>', Carbon::now())->get());
        return $activities->merge($racingExcellences)->sortBy('start');
    }
    
    public function upcomingActivities()
    {
        // dd(Carbon::now());
        return $this->activities()->with('type')->where('start', '>', Carbon::now())->orderBy('start');
    }

    public function dashboardUpcomingActivities()
    {
        return $this->upcomingActivities()->take(10);
    }

    public function recentActivities()
    {
        return $this->activities()->with('type')->where('end', '<', Carbon::now())->orderBy('end', 'desc');
    }

    public function dashboardRecentActivities()
    {
        return $this->recentActivities()->take(10);
    }

    /*
        Get activities for this month that have the jockey in and get the duration.
        If activity is a group activity divide duration by number of jockeys and 
        rounded down to nearest whole number.
        Sum the duration and divide by 60 to get in hours.
     */
    public function trainingTimeThisMonth()
    {   
        $activities =  $this->activities()
            ->with('jockeys')
            ->whereBetween('end', [Carbon::now()->startOfMonth(), Carbon::now()])
            ->get();

        $duration = $activities->sum(function($activity){
            return floor($activity->duration / $activity->jockeys->count());
        });

        return round($duration / 60, 2); // round to two decimal places.
    }

    public function trainingTimeThisMonthPercentage()
    {
        return ($this->trainingTimeThisMonth() / 4) * 100; // to nearest whole number?
    }
}
