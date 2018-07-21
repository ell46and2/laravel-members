<?php

namespace App\Models;

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

    public function activitiesNotYetInvoicedCount()
    {
        return $this->activities()
            ->where('start', '<', now())
            ->doesntHave('invoiceLine')
            ->count();
    }

    public function competencyAssessments()
    {
        return $this->hasMany(CompetencyAssessment::class);
    }

    public function racingExcellences()
    {
        return $this->hasMany(RacingExcellence::class, 'coach_id');
    }

    public function racingExcellencesNotYetInvoicedCount()
    {
        return $this->racingExcellences()
            ->where('start', '<', now())
            ->doesntHave('invoiceLine')
            ->count();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'coach_id');
    }

    public function latestOpenInvoice()
    {
        return $this->hasOne(Invoice::class, 'coach_id')->where('status', 'pending submission');
    }

    public function pendingReviewInvoice()
    {
        return $this->hasOne(Invoice::class, 'coach_id')->where('status', 'pending review');
    }

    public function mileages()
    {
        return $this->hasMany(CoachMileage::class, 'coach_id');
    }

    public function currentMileage()
    {
        return $this->hasOne(CoachMileage::class, 'coach_id')->where('year', now()->year);
    }

    public function lastYearsMileage()
    {
        return $this->hasOne(CoachMileage::class, 'coach_id')->where('year', now()->subYear()->year);
    }

    /*
        Utilities
     */
    public function lastSubmittedInvoice()
    {
        return $this->invoices->where('status', '!=', 'pending submission')->last();
    }

    public function hasOpenInvoice()
    {
        return (bool) $this->latestOpenInvoice()->count();
    }

    public function canCreateInvoice()
    {
        // No current invoice && not already submitted for the month
        if(!$this->hasOpenInvoice()) {
            if(Invoice::inSubmitInvoicePeriod() 
                && optional(optional($this->lastSubmittedInvoice())->submitted)->month === now()->month
            ) {
                return false;
            }
            return true;
        }
        return false;
    }

    public static function mostActive()
    {
        $coaches = self::with([
            'activities' => function($q) {
                $q->where('start', '<', now());
                $q->orderBy('start', 'desc');
            },
            'jockeys',
            'pendingReviewInvoice'
        ])
        ->get();

        return $coaches->sortByDesc(function($coach) {
            if($coach->activities->count()) {
                return $coach->activities->first()->start;
            }          
            return null;
        });
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
        // NOTE: should this be all racingExcellence where coach is the assigned coach
        // and also any racingExcellence that contains one of their assigned Jockeys?
        
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
            ->whereBetween('end', [now()->startOfMonth(), now()])
            ->whereHas('jockeys', function($query) use ($jockeyId) {
                $query->where('id', $jockeyId);
            })
            ->get();

        $duration = $activities->sum(function($activity){
            return floor($activity->duration / $activity->jockeys->count());
        });

        return round($duration / 60, 2);
    }

    public function overallTrainingTimeThisMonth()
    {
        $duration = $this->activities()
            ->whereBetween('end', [now()->startOfMonth(), now()])
            ->sum('duration');

        return round($duration / 60, 2);
    }

    public function activitiesInNextSevenDays()
    {
        return $this->activities()
            ->whereBetween('start', [now(), now()->addDays(7)])
            ->count();
    }

    public function upcomingActivities()
    {
        return $this->activities()->with('type', 'location', 'jockeys')->where('start', '>',now())->orderBy('start');
    }

    public function dashboardUpcomingActivities()
    {
        return $this->upcomingActivities()->take(10);
    }

    public function recentActivities()
    {
        return $this->activities()->with('type', 'location', 'jockeys')->where('end', '<', now())->orderBy('end', 'desc');
    }

    public function dashboardRecentActivities()
    {
        return $this->recentActivities()->take(10);
    }

    public function lastActivityDateColourCode($jockey)
    {
        $lastActivity = $this->activities()
            ->with('jockeys')
            ->where('start', '<', now())
            ->whereHas('jockeys', function($query) use ($jockey) {
                $query->where('id', $jockey->id);
            })
            ->orderBy('start', 'desc')
            ->first();

        if(!$lastActivity) {
            return 'blue';
        }

        if($lastActivity->start > now()->subWeeks(2)) {
            return 'green';
        } else if($lastActivity->start > now()->subWeeks(4)) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    public function invoiceableList()
    {
        // NOTE: if current days is between 1st and 10th, don't show any
        // invoiceables for the current month
        
        // NOTE: if Admin we sub 4 months
        $subMonths = 2;
        if(auth()->user()->isAdmin()) {
            $subMonths = 4;
        }

        $invoiceables = (collect($this->invoiceableActivities($subMonths))
            ->merge(collect($this->invoiceableRacingExcellence($subMonths))))
            ->sortBy('start');

        if(Invoice::inSubmitInvoicePeriod()) { // exclude current months
            $invoiceables->where('start', '<', now()->startOfMonth());
        }

        return $invoiceables;
    }

    public function invoiceableActivities($subMonths)
    {
        return $this->activities()
            ->whereBetween('start', [now()->subMonths($subMonths), now()])
            ->whereNotNull('duration')
            ->doesntHave('invoiceLine')
            ->with('type')
            ->get();
    }

    public function invoiceableRacingExcellence($subMonths)
    {
        return $this->racingExcellences()
            ->whereBetween('start', [now()->subMonths($subMonths), now()])
            ->doesntHave('invoiceLine')
            ->get();
    }

    public function addInvoiceMilesToYearlyMileage(Invoice $invoice)
    {
        $currentYearInvoiceMileage = $invoice->invoiceMileage->mileages()->where('mileage_date', '>=', now()->startOfYear())->get();
        $currentYearInvoiceMiles = $currentYearInvoiceMileage->sum('miles');

        $this->currentMileage()->update([
            'miles' => toTwoDecimals($currentYearInvoiceMiles + $this->currentMileage->miles)
        ]);
        
        // if all mileage just from this year then return.
        if($invoice->invoiceMileage->mileages()->count() === $currentYearInvoiceMileage->count()) {
            return;
        }

        // we have mileage from last year
        $lastYearInvoiceMiles = $invoice->invoiceMileage->mileages()->where('mileage_date', '<', now()->startOfYear())->sum('miles');

        $this->lastYearsMileage()->update([
            'miles' => toTwoDecimals($lastYearInvoiceMiles + $this->lastYearsMileage->miles)
        ]);
    }

    public function isVatRegistered()
    {
        return $this->vat_number !== null;
    }
}
