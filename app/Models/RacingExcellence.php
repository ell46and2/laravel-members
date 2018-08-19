<?php

namespace App\Models;

use App\Filters\RacingExcellence\RacingExcellenceFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RacingExcellence extends Model
{ 
	protected $fillable = ['coach_id', 'start', 'location', 'series_name', 'raceId', 'yearOfRace'];

	protected $dates = ['created_at', 'updated_at', 'start'];

    protected $casts = [
        'completed' => 'boolean'
    ];

	/*
		Relationships
	*/
    public function participants()
    {
        return $this->hasManyThrough(
        	RacingExcellenceParticipant::class, 
        	RacingExcellenceDivision::class,
        	'racing_excellence_id',
        	'division_id',
        	'id',
        	'id'
        );
    }

    public function jockeyParticipants()
    {
    	return $this->participants()->whereNotNull('jockey_id');
    }

    public function jockeys()
    {     
        return $this->hasManyThrough(
            Jockey::class, 
            RacingExcellenceParticipant::class,
            'jockey_id',
            'id',
            'id',
            'id'
        );      
    }

    public function coach()
	{
		return $this->belongsTo(Coach::class, 'coach_id');
	}

	public function divisions()
	{
		return $this->hasMany(RacingExcellenceDivision::class);
	}

	public function location()
	{
		return $this->belongsTo(RacingLocation::class, 'location_id');
	}

	public function series()
	{
		return $this->belongsTo(SeriesType::class);
	}

	public function notifications()
    {
    	return $this->morphMany(Notification::class, 'notifiable');
    }

    public function invoiceLine()
    {
        return $this->morphOne(InvoiceLine::class, 'invoiceable');
    }

    
    /*
    	Utilities
    */
    public function invoicable()
    {   
        return is_null($this->invoiceLine);
    }

    public function divisionResults()
    {
        return $this->divisions()->with(['participants' => function($query) {
            $query->select(['*', DB::raw('IF(`place` IS NOT NULL, `place`, 1000000) `place`')]);
            $query->orderBy('place', 'asc');
        },
        'participants.jockey'
        ])->get();
    }

	public static function createRace(Request $request)
	{
		$racingExcellence = self::create([
            'coach_id' => $request->coach_id,
            'location_id' => $request->location_id,
            'series_id' => $request->series_id,
            'start' => Carbon::createFromFormat('d/m/Y H:i',"{$request->start_date} {$request->start_time}"),
		]);

		$racingExcellence->addDivisions(collect(request()->only(['divisions'])['divisions']));

		return $racingExcellence;
	}

	public function addDivisions(Collection $divisionsData)
	{
        // dd($divisionsData);

		$divisionsData->each(function($divisionData) {
            // dd($divisionData);		
			$division = $this->divisions()->create();
            if(array_key_exists('jockeys', $divisionData)) {
              $division->addJockeysById(collect(array_keys($divisionData['jockeys'])));  
            }
			if(array_key_exists('external_participants', $divisionData)) {
			 $division->addExternalParticipants(collect(array_keys($divisionData['external_participants'])));
            }
		});
	}

    public function hasCoach()
    {
        return $this->coach_id !== null;
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new RacingExcellenceFilters($request))->filter($builder);
    }

    /*
        Attributes
     */

	public function getFormattedTypeAttribute()
    {
        return 'Racing Excellence';
    }

    public function getIconAttribute()
    {
        return 'nav-racing-excellence';
    }

    public function getFormattedStartAttribute()
    {
        return $this->start->format('l jS F Y');
    }

    public function getFormattedStartDayMonthAttribute()
    {
        return $this->start->format('l jS F');
    }

    public function getStartDateAttribute()
    {
        return $this->start->format('d/m/Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start->format('H:i');
    }

    public function getFormattedStartTimeFullAttribute()
    {
        return $this->start->format('H:ia');
    }

    public function getFormattedLocationAttribute()
    {
        return ucfirst($this->location);
    }

    public function getFormattedJockeyOrGroupAttribute()
    {
        return 'Group';
    }

    public function getFormattedSeriesNameAttribute()
    {
        return $this->series_name;
    }

    public function getNotificationLinkAttribute()
    {
        // if assigned coach send to results page - do we just redirect them from the controller?
        
        return "/racing-excellence/{$this->id}";
    }

    public function getInvoiceableGroupAttribute()
    {
        return 'racingexcellences';
    }

    public function getNumDivisionsAttribute()
    {
        return $this->divisions->count();
    }

    public function getFormattedCoachAttribute()
    {
        if(!$this->coach_id) {
            return '-';
        }

        return $this->coach->full_name;
    }
}
