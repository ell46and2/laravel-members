<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Coach extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            // $query->whereHas('role', function($q) {
            // 	$q->where('name', 'coach');
            // });
            $query->where('role_id', 2);
        });
    }

    /*
    	Relationships
    */
   
   	public function jockeys() // could order alphabetically here ->orderBy('last_name', 'desc')
    {
        return $this->belongsToMany(Jockey::class, 'coach_jockey', 'coach_id', 'jockey_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'coach_id');
    }

    public function competencyAssessments()
    {
        return $this->hasMany(CompetencyAssessment::class);
    }

    public function racingExcellences()
    {
        return $this->hasMany(RacingExcellence::class, 'coach_id');
    } 

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'coach_id');
    }

    public function latestOpenInvoice()
    {
        return $this->hasOne(Invoice::class, 'coach_id')->where('status', 'pending submission');
    }

    /*
        Utilities
     */
    public function hasOpenInvoice()
    {
        return (bool) $this->latestOpenInvoice()->count();
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

    public function getEventActivities($request)
    {
        if(is_numeric($request->type) || !$request->type) {
            return $this->activities()->with([
                'jockeys',
                'type',
                'location',
                'comments',
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
                'jockeys',
                'location',
            ])
            ->filter($request)
            ->get();
        }

        return [];
    }

    public function getEventCompetencyAssessments($request)
    {
        if($request->type === 'ca' || !$request->type) {
            return $this->competencyAssessments()
                ->with(['jockey'])->filter($request)->get();
        }

        return [];
    }

    
    public function scopeByAccessToken(Builder $builder, $email, $token)
    {
        return $builder->where('email', $email)->where('access_token', $token)->whereNotNull('access_token');
    }
    
    public function numberOfJockeysCoaching()
    {
        return $this->jockeys()->count();
    }

    public static function createNew($requestData)
    {
        $data = array_merge($requestData, [ 
            'password' => uniqid(true),
            'role_id' => Role::where('name', 'coach')->firstOrFail()->id,
            'approved' => true,
            'access_token' => str_random(100),
        ]);

        return self::create($data);
    }

    public function assignJockey(Jockey $jockey)
    {
        $this->jockeys()->attach($jockey->id);
    }

    public function unassignJockey(Jockey $jockey)
    {
        $this->jockeys()->detach($jockey->id);
    }

    /*
        Get activities for this month that have the jockey in and get the duration.
        If activity is a group activity divide duration by number of jockeys and 
        rounded down to nearest whole number.
        Sum the duration and divide by 60 to get in hours.
    */
    public function trainingTimeWithJockeyThisMonth($jockeyId)
    {   
        $activities = $this->activities()
            ->with('jockeys')
            ->whereBetween('end', [Carbon::now()->startOfMonth(), Carbon::now()])
            ->whereHas('jockeys', function($query) use ($jockeyId) {
                $query->where('id', $jockeyId);
            })
            ->get();

        $duration = $activities->sum(function($activity){
            return floor($activity->duration / $activity->jockeys->count());
        });

        return round($duration / 60, 2);
    }

    public function activitiesInNextSevenDays()
    {
        return $this->activities()
            ->whereBetween('start', [Carbon::now(), Carbon::now()->addDays(7)])
            ->count();
    }

    public function lastActivityDateColourCode($jockey)
    {
        $lastActivity = $this->activities()
            ->with('jockeys')
            ->where('start', '<', Carbon::now())
            ->whereHas('jockeys', function($query) use ($jockey) {
                $query->where('id', $jockey->id);
            })
            ->orderBy('start', 'desc')
            ->first();

        if(!$lastActivity) {
            return 'blue';
        }

        if($lastActivity->start > Carbon::now()->subWeeks(2)) {
            return 'green';
        } else if($lastActivity->start > Carbon::now()->subWeeks(4)) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    public function invoiceableList()
    {
        return (collect($this->invoiceableActivities())
            ->merge(collect($this->invoiceableRacingExcellence())))
            ->sortBy('start');
    }

    public function invoiceableActivities()
    {
        return $this->activities()
            ->whereBetween('start', [Carbon::now()->subMonths(2), Carbon::now()])
            ->whereNotNull('duration')
            ->doesntHave('invoiceLine')
            ->with('type')
            ->get();
    }

    public function invoiceableRacingExcellence()
    {
        return $this->racingExcellences()
            ->whereBetween('start', [Carbon::now()->subMonths(2), Carbon::now()])
            ->doesntHave('invoiceLine')
            ->get();
    }
}
