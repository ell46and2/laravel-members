<?php

namespace App\Models;

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
        return $this->belongsToMany(Coach::class, 'coach_jockey', 'jockey_id', 'coach_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_jockey', 'jockey_id', 'activity_id')
            ->withPivot('feedback');
    }

    public function competencyAssessments()
    {
        return $this->hasMany(CompetencyAssessment::class, 'jockey_id');
    }

    public function racingExcellenceDivisions()
    {
           return $this->belongsToMany(RacingExcellenceDivision::class, 'racing_excellence_participants', 'jockey_id', 'division_id');
    }

    public function racingExcellences()
    {
        return $this->hasManyThrough(
            RacingExcellence::class, 
            RacingExcellenceParticipant::class,
            'jockey_id',
            'id',
            'id',
            'racing_excellence_id'
        )->distinct();
    }

    /*
        Scopes
    */
    public function scopeActive($query)
    {
       return $query->where('approved', true)->where('status', '!=', 'deleted'); 
    }

    /*
    	Utilities
     */
    public static function awaitingApproval()
    {
        return self::where('approved', false);
    }
    
    public function events(Request $request)
    {
        // NOTE: need to default to go back 2 years max as default
        // and if 'from' request is present remove the default.  

        $activities = collect($this->getEventActivities($request));

        $racingExcellences = collect($this->getEventRacingExcellence($request));

        $competencyAssessments = collect($this->getEventCompetencyAssessments($request));

        $events = ($activities->merge($racingExcellences))
            ->merge($competencyAssessments);

        if($request->order === 'desc') {
            return $events->sortByDesc('start');
        }

        return $events->sortBy('start');
    }

    public function getEventCompetencyAssessments($request)
    {
        if($request->type === 'ca' || !$request->type) {
            return $this->competencyAssessments()
                ->with(['coach'])->filter($request)->get();
        }

        return [];
    }

    public function getEventActivities($request)
    {
        if(is_numeric($request->type) || !$request->type) {
            return $this->activities()->with([
                'coach',
                'type',
                'location',
                'commentsForOrFromJockey',
                'unreadCommentsOnActivityForCurentUser',
            ])
            ->filter($request)
            ->get();
        }

        return [];
    }

    public function getEventRacingExcellence($request)
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
        $racingExcellences = collect($this->racingExcellences()
            ->whereDate('start', '>', now())
            ->get());

        return $activities->merge($racingExcellences)->sortBy('start');
    }
    
    public function upcomingActivities()
    {
        return $this->activities()->with('type', 'coach', 'location')->where('start', '>', now())->orderBy('start');
    }

    public function dashboardUpcomingActivities()
    {
        return $this->upcomingActivities()->take(10);
    }

    public function recentActivities()
    {
        return $this->activities()->with('type', 'coach', 'location')->where('start', '<', now())->orderBy('start', 'desc');
    }

    public function dashboardRecentActivities()
    {
        return $this->recentActivities()->take(10);
    }

    public function lastFiveActivities()
    {
        return $this->recentActivities()->take(5);
    }

    public function lastFiveRacingExcellence()
    {
        return $this->racingExcellences()->with('coach')->orderBy('start', 'desc')->take(5);
    }

    public function trainingAllowanceThisMonth()
    {
        $licenceDate = $this->licence_date;

        if(!$licenceDate) return 4;

        if($licenceDate->day < 15) {
            $cutOff = $licenceDate->addMonths(2)->endOfMonth();
        } else {
            $cutOff = $licenceDate->addMonths(3)->endOfMonth();
        }

        return $cutOff > now() ? 6 : 4;
    }

    /*
        Get activities for this month that have the jockey in and get the duration.
        If activity is a group activity divide duration by number of jockeys and 
        rounded down to nearest whole number.
        Sum the duration and divide by 60 to get in hours.
     */
    public function trainingTimeThisMonth($forInvoice = false)
    {   
        $activities = $this->activitiesThisMonth($forInvoice);

        $duration = $activities->sum(function($activity){
            return floor($activity->duration / $activity->jockeys->count());
        });

        return round($duration / 60, 2); // round to two decimal places.
    }

    public function activitiesThisMonth($forInvoice)
    {
        $times = $this->getTrainingTimeStartAndEnd($forInvoice);

        return $this->activities()
            ->with('jockeys')
            ->whereBetween('end', [$times->start, $times->end])
            ->get();
    }

    private function getTrainingTimeStartAndEnd($forInvoice)
    {
        $start = now()->startOfMonth();
        $end = now();

        if($forInvoice) {
            $currentDay = now()->day;
            if($currentDay >= config('jcp.invoice.start_period') && 
                $currentDay <= config('jcp.invoice.end_period')) 
            {
                $start = now()->subMonth()->startOfMonth();
                $end =  now()->subMonth()->endOfMonth();
            }
             
        }

        $times = new \stdClass();
        $times->start = $start;
        $times->end = $end;

        return $times;
    }

    public function trainingTimeThisMonthPercentage()
    {
        return ($this->trainingTimeThisMonth() / 4) * 100; // to nearest whole number?
    }

    public static function getExternalJockeyAvatar()
    {
        return config('jcp.buckets.avatars') . 'default_avatar.png';
    }

    public function isAssignedToCoach($coachId)
    {
        return $this->coaches->pluck('id')->contains($coachId);
    }

    /*
        Attributes
     */
    public function getFormattedPrizeMoneyAttribute()
    {
        if($this->prize_money === null) {
            return '-';
        }
        return '£' . invoiceNumberFormat($this->prize_money);
    }

    public function getFormattedAssociatedContentAttribute()
    {
        if($this->associated_content === null) {
            return '-';
        }
        return "<a href='{$this->associated_content}' target='_blank'>Stewards enquiries/reports</a>";
    }

    public function getFormattedWinsToRidesAttribute()
    {
        if($this->rides === null) {
            return '-';
        }
        return toOneDecimal(($this->wins / $this->rides) * 100) . '%'; 
    }

    public function getFormattedLicenceType()
    {
        if(!$this->licence_type) return '-';

        return $this->licence_type;
    }

    public function getFormattedRides()
    {
        if(!$this->rides) return '-';

        return $this->rides;
    }

    public function getFormattedWins()
    {
        if(!$this->rides) return '-';

        return $this->rides;
    }

    public function getFormattedTrainer()
    {
        if(!$this->trainer_name) return '-';

        return $this->trainer_name;
    }
}
