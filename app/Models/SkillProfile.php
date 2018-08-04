<?php

namespace App\Models;

use App\Filters\SkillProfile\SkillProfileFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SkillProfile extends Model
{
	protected $guarded = [];

	protected $dates = ['created_at', 'updated_at', 'start'];

	/*
		Relationships
	*/
    public function coach()
	{
		return $this->belongsTo(Coach::class, 'coach_id');
	}

	public function jockey()
	{
		return $this->belongsTo(Jockey::class, 'jockey_id');
	}

	public function notifications()
    {
    	return $this->morphMany(Notification::class, 'notifiable');
    }

    /*
    	Utilities
    */
   	public function scopeFilter(Builder $builder, $request)
    {
        return (new SkillProfileFilters($request))->filter($builder);
    }

    public function getFormattedTypeAttribute()
    {
        return 'Skills Profile';
    }

    public function getIconAttribute()
    {
        return 'nav-skills-profile';
    }

    public function getFormattedStartAttribute()
    {
        return $this->start->format('l jS F Y');
    }

    public function getStartDateAttribute()
    {
        return $this->start->format('d/m/Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start->format('H:i');
    }

    public function getFormattedLocationAttribute()
    {
        return '-';
    }

    public function getFormattedJockeyOrGroupAttribute()
    {
        return $this->jockey->full_name;      
    }
}
