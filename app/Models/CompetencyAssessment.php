<?php

namespace App\Models;

use App\Filters\CompetencyAssessment\CompetencyAssessmentFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CompetencyAssessment extends Model
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
        return (new CompetencyAssessmentFilters($request))->filter($builder);
    }

    public function getFormattedTypeAttribute()
    {
        return 'Competency Assessment';
    }

    public function getFormattedStartAttribute()
    {
        return $this->start->format('l jS F Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return '-';
    }

    public function getFormattedLocationAttribute()
    {
        return '-';
    }
}
