<?php

namespace Tests\Feature\Coach;

use App\Jobs\CompetencyAssessment\CreatedNotifyJockey;
use App\Models\Coach;
use App\Models\CompetencyAssessment;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CompetencyAssessmentTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
        	'jockey_id' => 2,
            'start_date' => '02/05/2019',
            'start_time' => '18:00',
            'riding_rating' => 0.5,
            'riding_observation' => 'New riding observation',
            'fitness_rating' => 1,
            'fitness_observation' => 'New fitness observation',
            'simulator_rating' => 1.5,
            'simulator_observation' => 'New simulator observation',
            'race_riding_skills_rating' => 2,
            'race_riding_skills_observation' => 'New race riding skills observation',
            'weight_rating' => 2.5,
            'weight_observation' => 'New weight observation',
            'communication_rating' => 3,
            'communication_observation' => 'New communication observation',
            'racing_knowledge_rating' => 3.5,
            'racing_knowledge_observation' => 'New racing knowledge observation',
            'mental_rating' => 5,
            'mental_observation' => 'New mental observation',
            'summary' => 'summary',
        ], $overrides);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'start_date' => '11/12/2018',
            'start_time' => '13:00',
            'riding_rating' => 0.5,
            'riding_observation' => 'riding observation',
            'fitness_rating' => 1,
            'fitness_observation' => 'fitness observation',
            'simulator_rating' => 1.5,
            'simulator_observation' => 'simulator observation',
            'race_riding_skills_rating' => 2,
            'race_riding_skills_observation' => 'race riding skills observation',
            'weight_rating' => 2.5,
            'weight_observation' => 'weight observation',
            'communication_rating' => 3,
            'communication_observation' => 'communication observation',
            'racing_knowledge_rating' => 3.5,
            'racing_knowledge_observation' => 'racing knowledge observation',
            'mental_rating' => 5,
            'mental_observation' => 'mental observation',
            'summary' => 'summary',
        ], $overrides);
    }

    /** @test */
    public function a_coach_can_create_a_competency_assessment_for_one_of_their_jockeys()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $response = $this->actingAs($coach)->post("/coach/competency-assessment", [
        	'jockey_id' => $jockey->id,
            'start_date' => '11/12/2018',
            'start_time' => '13:00',
            'riding_rating' => 0.5,
            'riding_observation' => 'riding observation',
            'fitness_rating' => 1,
            'fitness_observation' => 'fitness observation',
            'simulator_rating' => 1.5,
            'simulator_observation' => 'simulator observation',
            'race_riding_skills_rating' => 2,
            'race_riding_skills_observation' => 'race riding skills observation',
            'weight_rating' => 2.5,
            'weight_observation' => 'weight observation',
            'communication_rating' => 3,
            'communication_observation' => 'communication observation',
            'racing_knowledge_rating' => 3.5,
            'racing_knowledge_observation' => 'racing knowledge observation',
            'mental_rating' => 5,
            'mental_observation' => 'mental observation',
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
         	$this->assertEquals($competencyAssessment->riding_observation, 'riding observation');
         	$this->assertEquals($competencyAssessment->fitness_rating, 1);
         	$this->assertEquals($competencyAssessment->fitness_observation, 'fitness observation');
         	$this->assertEquals($competencyAssessment->simulator_rating, 1.5);
         	$this->assertEquals($competencyAssessment->simulator_observation, 'simulator observation');
         	$this->assertEquals($competencyAssessment->race_riding_skills_rating, 2);
         	$this->assertEquals($competencyAssessment->race_riding_skills_observation, 'race riding skills observation');
         	$this->assertEquals($competencyAssessment->weight_rating, 2.5);
         	$this->assertEquals($competencyAssessment->weight_observation, 'weight observation');
         	$this->assertEquals($competencyAssessment->communication_rating, 3);
         	$this->assertEquals($competencyAssessment->communication_observation, 'communication observation');
         	$this->assertEquals($competencyAssessment->racing_knowledge_rating, 3.5);
         	$this->assertEquals($competencyAssessment->racing_knowledge_observation, 'racing knowledge observation');
         	$this->assertEquals($competencyAssessment->mental_rating, 5);
         	$this->assertEquals($competencyAssessment->mental_observation, 'mental observation');
         	$this->assertEquals($competencyAssessment->summary, 'summary');

         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });
         	
        });
    }

    /** @test */
    public function start_date_is_required()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'start_date' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function start_date_must_be_a_valid_date()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'start_date' => 'not-a-valid-date',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function start_time_is_required()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'start_time' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('start_time');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function start_time_must_be_a_valid_time()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'start_time' => 'not-a-time',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('start_time');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'riding_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'riding_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'riding_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function riding_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'riding_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function riding_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'riding_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function riding_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'riding_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->riding_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }

    /** @test */
    public function fitness_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'fitness_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function fitness_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'fitness_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function fitness_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'fitness_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function fitness_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'fitness_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function fitness_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'fitness_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function fitness_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'fitness_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->fitness_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }

    /** @test */
    public function simulator_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'simulator_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function simulator_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'simulator_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function simulator_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'simulator_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function simulator_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'simulator_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function simulator_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'simulator_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function simulator_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'simulator_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->simulator_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }

    /** @test */
    public function race_riding_skills_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'race_riding_skills_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function race_riding_skills_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'race_riding_skills_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function race_riding_skills_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'race_riding_skills_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function race_riding_skills_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'race_riding_skills_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function race_riding_skills_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'race_riding_skills_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function race_riding_skills_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'race_riding_skills_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->race_riding_skills_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }

    /** @test */
    public function weight_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'weight_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function weight_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'weight_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function weight_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'weight_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function weight_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'weight_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function weight_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'weight_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function weight_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'weight_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->weight_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }

    /** @test */
    public function communication_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'communication_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function communication_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'communication_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function communication_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'communication_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function communication_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'communication_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function communication_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'communication_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function communication_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'communication_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->communication_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }

    /** @test */
    public function racing_knowledge_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'racing_knowledge_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function racing_knowledge_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'racing_knowledge_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function racing_knowledge_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'racing_knowledge_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function racing_knowledge_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'racing_knowledge_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function racing_knowledge_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'racing_knowledge_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function racing_knowledge_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'racing_knowledge_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->racing_knowledge_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }

    /** @test */
    public function mental_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'mental_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('mental_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function mental_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'mental_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('mental_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function mental_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'mental_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('mental_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function mental_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'mental_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('mental_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function mental_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'mental_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/competency-assessment/create');
        $response->assertSessionHasErrors('mental_rating');
        $this->assertEquals(0, CompetencyAssessment::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function mental_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'mental_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->mental_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }

    /** @test */
    public function summary_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/competency-assessment/create")->post("/coach/competency-assessment", $this->validParams([
            'summary' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
        	// dd($competencyAssessment);
        	$response->assertStatus(302);
         	$response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
         	
         	$this->assertEquals($competencyAssessment->summary, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });       	
        });
    }
}