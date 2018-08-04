<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pdp extends Model
{
    protected $guarded = [];

    protected $casts = [
        'weight' => 'boolean',
        'confidence' => 'boolean',
        'strength_fitness' => 'boolean',
        'racing_preparation' => 'boolean',
        'diet' => 'boolean',
        'three_months_1_achieved' => 'boolean',
        'three_months_2_achieved' => 'boolean',
        'three_months_3_achieved' => 'boolean',
        'six_months_1_achieved' => 'boolean',
        'six_months_2_achieved' => 'boolean',
        'six_months_3_achieved' => 'boolean',
        'twelve_months_1_achieved' => 'boolean',
        'twelve_months_2_achieved' => 'boolean',
        'twelve_months_3_achieved' => 'boolean',
        'two_years_1_achieved' => 'boolean',
        'two_years_2_achieved' => 'boolean',
        'two_years_3_achieved' => 'boolean',
        'jets_actions_completed' => 'boolean',
        'support_team_actions_completed' => 'boolean',
        'personal_details_page_complete' => 'boolean',
        'career_page_complete' => 'boolean',
        'nutrition_page_complete' => 'boolean',
        'physical_page_complete' => 'boolean',
        'communication_media_page_complete' => 'boolean',
        'personal_well_being_page_complete' => 'boolean',
        'managing_finance_page_complete' => 'boolean',
        'sports_psychology_page_complete' => 'boolean',
        'mental_well_being_page_complete' => 'boolean',
        'interests_hobbies_page_complete' => 'boolean',
        'performance_goals_page_complete' => 'boolean',
        'actions_page_complete' => 'boolean',
        'support_team_page_complete' => 'boolean',
    ];

    protected $dates = ['created_at', 'updated_at', 'submitted'];

    /*
        Relationships
    */
    public function jockey()
    {
    	return $this->belongsTo(Jockey::class);
    }

    /*
        Attributes    
    */
    public function getNameAttribute()
    {
        if(!$this->submitted) {
            return 'Current';
        }

        return "PDP {$this->submitted->format('F Y')}";
    }

    public function getFormattedSubmittedAttribute()
    {
        if(!$this->submitted) {
            return '-';
        }

        return $this->submitted->format('l jS F');
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at->format('l jS F');
    }

    public function getGoalsAchievedAttribute()
    {
        $count = 0;

        $goalNames = ['three_months', 'six_months', 'twelve_months', 'two_years'];

        foreach ($goalNames as $goalName) {
            for ($i=0; $i <= 3; $i++) { 
                if($this->{$goalName . '_' . $i . '_achieved'}) {
                    $count++;
                }
            }
        } 

        return $count;  
    }

    public function getPercentageCompleteAttribute()
    {
        $pages = collect(config('jcp.pdp_fields'))->pluck('field');

        $numCompleted = 0;

        foreach ($pages as $page) {
            if($this->{$page . '_page_complete'}) {
               $numCompleted++; 
            } 
        }

        return number_format((($numCompleted / $pages->count()) * 100), 0, '.', '');
    }

    public function getDaysTillPerformanceReviewAttribute()
    {
        $threeMonths = now()->diffInDays($this->submitted->addMonths(3));
        if($threeMonths > 0) return $threeMonths;

        $sixMonths = now()->diffInDays($this->submitted->addMonths(6));
        if($sixMonths > 0) return $sixMonths;

        $twelveMonths = now()->diffInDays($this->submitted->addMonths(12));
        if($twelveMonths > 0) return $twelveMonths;
    }
}
