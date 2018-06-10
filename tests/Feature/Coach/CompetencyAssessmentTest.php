<?php

namespace Tests\Feature\Coach;

use App\Models\Coach;
use App\Models\CompetencyAssessment;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CompetencyAssessmentTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'start_date' => '02/05/2019',
            'start_time' => '18:00',
            'riding_rating' => 0.5,
            'riding_feedback' => 'riding feedback',
            'fitness_rating' => 1,
            'fitness_feedback' => 'fitness feedback',
            'simulator_rating' => 1.5,
            'simulator_feedback' => 'simulator feedback',
            'race_riding_skills_rating' => 2,
            'race_riding_skills_feedback' => 'race riding skills feedback',
            'weight_rating' => 2.5,
            'weight_feedback' => 'weight feedback',
            'communication_rating' => 3,
            'communication_feedback' => 'communication feedback',
            'racing_knowledge_rating' => 3.5,
            'racing_knowledge_feedback' => 'racing knowledge feedback',
            'mental_rating' => 5,
            'mental_feedback' => 'mental feedback',
            'summary' => 'summary',
        ], $overrides);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'start_date' => '11/12/2018',
            'start_time' => '13:00',
            'riding_rating' => 0.5,
            'riding_feedback' => 'riding feedback',
            'fitness_rating' => 1,
            'fitness_feedback' => 'fitness feedback',
            'simulator_rating' => 1.5,
            'simulator_feedback' => 'simulator feedback',
            'race_riding_skills_rating' => 2,
            'race_riding_skills_feedback' => 'race riding skills feedback',
            'weight_rating' => 2.5,
            'weight_feedback' => 'weight feedback',
            'communication_rating' => 3,
            'communication_feedback' => 'communication feedback',
            'racing_knowledge_rating' => 3.5,
            'racing_knowledge_feedback' => 'racing knowledge feedback',
            'mental_rating' => 5,
            'mental_feedback' => 'mental feedback',
            'summary' => 'summary',
        ], $overrides);
    }

    /** @test */
    public function a_coach_can_create_a_competency_assessment_for_one_of_their_jockeys()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $response = $this->actingAs($coach)->post("/coach/competency-assessment", [
        	'jockey_id' => $jockey->id,
            'start_date' => '11/12/2018',
            'start_time' => '13:00',
            'riding_rating' => 0.5,
            'riding_feedback' => 'riding feedback',
            'fitness_rating' => 1,
            'fitness_feedback' => 'fitness feedback',
            'simulator_rating' => 1.5,
            'simulator_feedback' => 'simulator feedback',
            'race_riding_skills_rating' => 2,
            'race_riding_skills_feedback' => 'race riding skills feedback',
            'weight_rating' => 2.5,
            'weight_feedback' => 'weight feedback',
            'communication_rating' => 3,
            'communication_feedback' => 'communication feedback',
            'racing_knowledge_rating' => 3.5,
            'racing_knowledge_feedback' => 'racing knowledge feedback',
            'mental_rating' => 5,
            'mental_feedback' => 'mental feedback',
            'summary' => 'summary',
        ]);

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->coach_id, $coach->id);
         	$this->assertEquals($competencyAssessment->jockey_id, $jockey->id);
         	$this->assertEquals(Carbon::parse($competencyAssessment->start), Carbon::parse('11/12/2018 13:00'));
         	$this->assertEquals($competencyAssessment->riding_rating, 0.5);
         	$this->assertEquals($competencyAssessment->riding_feedback, 'riding feedback');
         	$this->assertEquals($competencyAssessment->fitness_rating, 1);
         	$this->assertEquals($competencyAssessment->fitness_feedback, 'fitness feedback');
         	$this->assertEquals($competencyAssessment->simulator_rating, 1.5);
         	$this->assertEquals($competencyAssessment->simulator_feedback, 'simulator feedback');
         	$this->assertEquals($competencyAssessment->race_riding_skills_rating, 2);
         	$this->assertEquals($competencyAssessment->race_riding_skills_feedback, 'race riding skills feedback');
         	$this->assertEquals($competencyAssessment->weight_rating, 2.5);
         	$this->assertEquals($competencyAssessment->weight_feedback, 'weight feedback');
         	$this->assertEquals($competencyAssessment->communication_rating, 3);
         	$this->assertEquals($competencyAssessment->communication_feedback, 'communication feedback');
         	$this->assertEquals($competencyAssessment->racing_knowledge_rating, 3.5);
         	$this->assertEquals($competencyAssessment->racing_knowledge_feedback, 'racing knowledge feedback');
         	$this->assertEquals($competencyAssessment->mental_rating, 5);
         	$this->assertEquals($competencyAssessment->mental_feedback, 'mental feedback');
         	$this->assertEquals($competencyAssessment->summary, 'summary');
        });
    }

    /** @test */
    public function a_coach_can_edit_a_competency_assessment_they_have_created()
    {
    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $competencyAssessment = factory(CompetencyAssessment::class)->create([
        	'coach_id' => $coach->id,
        	'jockey_id' => $jockey->id
        ]);


    }

    /** @test */
    public function a_coach_cannot_edit_a_competency_assessment_they_have_not_created()
    {
            
    }
}