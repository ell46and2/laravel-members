<?php

namespace App\Http\Controllers\CompetencyAssessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompetencyAssessment\StorePostFormRequest;
use App\Http\Requests\CompetencyAssessment\UpdatePostFormRequest;
use App\Jobs\CompetencyAssessment\CreatedNotifyJockey;
use App\Jobs\CompetencyAssessment\UpdatedNotifyJockey;
use App\Models\CompetencyAssessment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompetencyAssessmentController extends Controller
{
	public function show(CompetencyAssessment $competencyAssessment)
	{
		
	}

    public function store(StorePostFormRequest $request)
    {
    	$competencyAssessment = $request->user()
    		->competencyAssessments()
    		->create(array_merge($request->only([
    		'jockey_id',
            'riding_rating',
            'riding_observation',
            'fitness_rating',
            'fitness_observation',
            'simulator_rating',
            'simulator_observation',
            'race_riding_skills_rating',
            'race_riding_skills_observation',
            'weight_rating',
            'weight_observation',
            'communication_rating',
            'communication_observation',
            'racing_knowledge_rating',
            'racing_knowledge_observation',
            'mental_rating',
            'mental_observation',
            'summary',
	    	]), [
	    		'start' => Carbon::parse("{$request->start_date} {$request->start_time}")
	    	])
	    );

       	$this->dispatch(new CreatedNotifyJockey($competencyAssessment));

    	return redirect()->route('competency-assessment.show', $competencyAssessment);
    }

    public function edit(CompetencyAssessment $competencyAssessment)
    {

    }

    public function update(UpdatePostFormRequest $request, CompetencyAssessment $competencyAssessment)
    {
    	$competencyAssessment->update(array_merge($request->only([
            'riding_rating',
            'riding_observation',
            'fitness_rating',
            'fitness_observation',
            'simulator_rating',
            'simulator_observation',
            'race_riding_skills_rating',
            'race_riding_skills_observation',
            'weight_rating',
            'weight_observation',
            'communication_rating',
            'communication_observation',
            'racing_knowledge_rating',
            'racing_knowledge_observation',
            'mental_rating',
            'mental_observation',
            'summary',
	    	]), [
	    		'start' => Carbon::parse("{$request->start_date} {$request->start_time}")
	    	])
    	);

    	$this->dispatch(new UpdatedNotifyJockey($competencyAssessment));
    	
    	return redirect()->route('competency-assessment.show', $competencyAssessment);
    }
}
