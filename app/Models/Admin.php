<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Admin extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            // $query->whereHas('role', function($q) {
            // 	$q->where('name', 'admin');
            // });
            $query->where('role_id', 3);
        });
    }

    public function events(Request $request)
    {
        // NOTE: need to default to go back 2 years max as default
        // and if 'from' request is present remove the default.  

        $activities = collect($this->getEventActivities($request));

        $racingExcellences = collect($this->getEventRacingExcellence($request));

        $skillProfiles = collect($this->getEventSkillProfiles($request));

        $events = ($activities->merge($racingExcellences))
            ->merge($skillProfiles);

        if($request->order === 'desc') {
            return $events->sortByDesc('start');
        }

        return $events->sortBy('start');
    }

    public function getEventActivities($request)
    {
        if(is_numeric($request->type) || !$request->type) {
            return Activity::with([
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
            return RacingExcellence::with([
                'jockeys',
                'location',
            ])
            ->filter($request)
            ->get();
        }

        return [];
    }

    public function getEventSkillProfiles($request)
    {
        if($request->type === 'ca' || !$request->type) {
            return SkillProfile::with(['jockey'])->filter($request)->get();
        }

        return [];
    }
}
