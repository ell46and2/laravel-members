<?php

namespace App\Http\Controllers\CompetencyAssessment;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompetencyAssessmentController extends Controller
{
	public function show(CompetencyAssessment $competencyAssessment)
	{
		
	}

    public function store(Request $request) // need to add form  request validation
    {
    	$competencyAssessment = $request->user()->competencyAssessments()->create(array_merge($request->only([
    		'jockey_id',
            'riding_rating',
            'riding_feedback',
            'fitness_rating',
            'fitness_feedback',
            'simulator_rating',
            'simulator_feedback',
            'race_riding_skills_rating',
            'race_riding_skills_feedback',
            'weight_rating',
            'weight_feedback',
            'communication_rating',
            'communication_feedback',
            'racing_knowledge_rating',
            'racing_knowledge_feedback',
            'mental_rating',
            'mental_feedback',
            'summary',
    	]), [
    		'start' => Carbon::parse("{$request->start_date} {$request->start_time}")
    	]));

    	return redirect()->route('competency-assessment.show', $competencyAssessment);
    }
}
