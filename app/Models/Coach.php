<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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
        return $this->belongsToMany(User::class, 'coach_jockey', 'jockey_id', 'coach_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'coach_id');
    }

    public function competencyAssessments()
    {
        return $this->hasMany(CompetencyAssessment::class);
    }


    /*
        Utilities
     */
    
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

    public function trainingTimeWithJockeyThisMonth($jockeyId)
    {   
        $startOfMonth = Carbon::now()->startOfMonth();

        // dd($startOfMonth);

        return $this->activities()
            ->whereBetween('end', [$startOfMonth, Carbon::now()])
            ->whereHas('jockeys', function($query) use ($jockeyId) {
                $query->where('id', $jockeyId);
            })
            ->sum('duration') / 60;

        // get activities for this month that have the jockey in.
        // sum the duration and divide by 60 to get in hours..
    }

    public function activitiesInNextSevenDays()
    {
        return $this->activities()
            ->whereBetween('start', [Carbon::now(), Carbon::now()->addDays(7)])
            ->count();
    }
}
