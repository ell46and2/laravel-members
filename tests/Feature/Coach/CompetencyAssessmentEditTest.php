<?php

namespace Tests\Feature\Coach;

use App\Jobs\CompetencyAssessment\UpdatedNotifyJockey;
use App\Models\Coach;
use App\Models\CompetencyAssessment;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CompetencyAssessmentEditTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'start_date' => '02/05/2019',
            'start_time' => '18:00',
            'riding_rating' => 5,
            'riding_observation' => 'New riding observation',
            'fitness_rating' => 5,
            'fitness_observation' => 'New fitness observation',
            'simulator_rating' => 5,
            'simulator_observation' => 'New simulator observation',
            'race_riding_skills_rating' => 5,
            'race_riding_skills_observation' => 'New race riding skills observation',
            'weight_rating' => 5,
            'weight_observation' => 'New weight observation',
            'communication_rating' => 5,
            'communication_observation' => 'New communication observation',
            'racing_knowledge_rating' => 5,
            'racing_knowledge_observation' => 'New racing knowledge observation',
            'mental_rating' => 5,
            'mental_observation' => 'New mental observation',
            'summary' => 'New summary',
        ], $overrides);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'jockey_id' => 2,
            'start' => Carbon::parse('11/12/2018 13:00'),
            'riding_rating' => 1,
            'riding_observation' => 'riding observation',
            'fitness_rating' => 1,
            'fitness_observation' => 'fitness observation',
            'simulator_rating' => 1,
            'simulator_observation' => 'simulator observation',
            'race_riding_skills_rating' => 1,
            'race_riding_skills_observation' => 'race riding skills observation',
            'weight_rating' => 1,
            'weight_observation' => 'weight observation',
            'communication_rating' => 1,
            'communication_observation' => 'communication observation',
            'racing_knowledge_rating' => 1,
            'racing_knowledge_observation' => 'racing knowledge observation',
            'mental_rating' => 1,
            'mental_observation' => 'mental observation',
            'summary' => 'summary',
        ], $overrides);
    }

    /** @test */
    public function a_coach_can_edit_a_competency_assessment_they_have_created()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $competencyAssessment = factory(CompetencyAssessment::class)->create([
            'coach_id' => $coach->id,
            'jockey_id' => $jockey->id,
            'start' => Carbon::parse('11/12/2018 13:00'),
            'riding_rating' => 1,
            'riding_observation' => 'riding observation',
            'fitness_rating' => 1,
            'fitness_observation' => 'fitness observation',
            'simulator_rating' => 1,
            'simulator_observation' => 'simulator observation',
            'race_riding_skills_rating' => 1,
            'race_riding_skills_observation' => 'race riding skills observation',
            'weight_rating' => 1,
            'weight_observation' => 'weight observation',
            'communication_rating' => 1,
            'communication_observation' => 'communication observation',
            'racing_knowledge_rating' => 1,
            'racing_knowledge_observation' => 'racing knowledge observation',
            'mental_rating' => 1,
            'mental_observation' => 'mental observation',
            'summary' => 'summary',
        ]);

        $response = $this->actingAs($coach)
            ->from("/competency-assessment/{$competencyAssessment->id}/edit")
            ->put("/competency-assessment/{$competencyAssessment->id}/update", [
                'start_date' => '02/05/2019',
                'start_time' => '18:00',
                'riding_rating' => 5,
                'riding_observation' => 'New riding observation',
                'fitness_rating' => 5,
                'fitness_observation' => 'New fitness observation',
                'simulator_rating' => 5,
                'simulator_observation' => 'New simulator observation',
                'race_riding_skills_rating' => 5,
                'race_riding_skills_observation' => 'New race riding skills observation',
                'weight_rating' => 5,
                'weight_observation' => 'New weight observation',
                'communication_rating' => 5,
                'communication_observation' => 'New communication observation',
                'racing_knowledge_rating' => 5,
                'racing_knowledge_observation' => 'New racing knowledge observation',
                'mental_rating' => 5,
                'mental_observation' => 'New mental observation',
                'summary' => 'New summary'
            ]);

        tap($competencyAssessment->fresh(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals(Carbon::parse($competencyAssessment->start), Carbon::parse('02/05/2019 18:00'));
            $this->assertEquals($competencyAssessment->riding_rating, 5);
            $this->assertEquals($competencyAssessment->riding_observation, 'New riding observation');
            $this->assertEquals($competencyAssessment->fitness_rating, 5);
            $this->assertEquals($competencyAssessment->fitness_observation, 'New fitness observation');
            $this->assertEquals($competencyAssessment->simulator_rating, 5);
            $this->assertEquals($competencyAssessment->simulator_observation, 'New simulator observation');
            $this->assertEquals($competencyAssessment->race_riding_skills_rating, 5);
            $this->assertEquals($competencyAssessment->race_riding_skills_observation, 'New race riding skills observation');
            $this->assertEquals($competencyAssessment->weight_rating, 5);
            $this->assertEquals($competencyAssessment->weight_observation, 'New weight observation');
            $this->assertEquals($competencyAssessment->communication_rating, 5);
            $this->assertEquals($competencyAssessment->communication_observation, 'New communication observation');
            $this->assertEquals($competencyAssessment->racing_knowledge_rating, 5);
            $this->assertEquals($competencyAssessment->racing_knowledge_observation, 'New racing knowledge observation');
            $this->assertEquals($competencyAssessment->mental_rating, 5);
            $this->assertEquals($competencyAssessment->mental_observation, 'New mental observation');
            $this->assertEquals($competencyAssessment->summary, 'New summary');

            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)
            ->from("/competency-assessment/{$competencyAssessment->id}/edit")
            ->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
                'start_date' => ''
            ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('start_date');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function start_date_must_be_a_valid_date()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)
            ->from("/competency-assessment/{$competencyAssessment->id}/edit")
            ->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
                'start_date' => 'not-a-valid-date'
            ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('start_date');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function start_time_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)
            ->from("/competency-assessment/{$competencyAssessment->id}/edit")
            ->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
                'start_time' => ''
            ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('start_time');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function start_time_must_be_a_valid_time()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)
            ->from("/competency-assessment/{$competencyAssessment->id}/edit")
            ->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
                'start_time' => 'not-a-time'
            ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('start_time');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'riding_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'riding_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'riding_rating' => -1,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function riding_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'riding_rating' => 6,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function riding_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'riding_rating' => 2.25,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function riding_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'riding_observation' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->riding_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'fitness_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function fitness_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'fitness_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function fitness_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'fitness_rating' => -1,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function fitness_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'fitness_rating' => 6,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function fitness_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'fitness_rating' => 2.25,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function fitness_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'fitness_observation' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->fitness_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'simulator_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function simulator_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'simulator_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function simulator_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'simulator_rating' => -1,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function simulator_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'simulator_rating' => 6,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function simulator_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'simulator_rating' => 2.25,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function simulator_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'simulator_observation' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->simulator_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'race_riding_skills_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function race_riding_skills_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'race_riding_skills_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function race_riding_skills_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'race_riding_skills_rating' => -1,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function race_riding_skills_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'race_riding_skills_rating' => 6,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function race_riding_skills_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'race_riding_skills_rating' => 2.25,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function race_riding_skills_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'race_riding_skills_observation' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->race_riding_skills_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'weight_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function weight_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'weight_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function weight_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'weight_rating' => -1,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function weight_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'weight_rating' => 6,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function weight_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'weight_rating' => 2.25,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function weight_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'weight_observation' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->weight_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'communication_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function communication_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'communication_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function communication_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'communication_rating' => -1,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function communication_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'communication_rating' => 6,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function communication_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'communication_rating' => 2.25,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function communication_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'communication_observation' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->communication_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'racing_knowledge_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function racing_knowledge_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'racing_knowledge_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function racing_knowledge_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'racing_knowledge_rating' => -1,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function racing_knowledge_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'racing_knowledge_rating' => 6,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function racing_knowledge_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'racing_knowledge_rating' => 2.25,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('racing_knowledge_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function racing_knowledge_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'racing_knowledge_observation' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->racing_knowledge_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'mental_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('mental_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function mental_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'mental_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('mental_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function mental_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'mental_rating' => -1,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('mental_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function mental_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'mental_rating' => 6,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('mental_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function mental_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'mental_rating' => 2.25,
        ]));

        // dd(CompetencyAssessment::first());

        $response->assertStatus(302);
        $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}/edit");
        $response->assertSessionHasErrors('mental_rating');
        $this->assertArraySubset($this->oldAttributes(), $competencyAssessment->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function mental_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'mental_observation' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->mental_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
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

        $competencyAssessment = factory(CompetencyAssessment::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/competency-assessment/{$competencyAssessment->id}/edit")->put("/competency-assessment/{$competencyAssessment->id}/update", $this->validParams([
            'summary' => '',
        ]));

        tap(CompetencyAssessment::first(), function($competencyAssessment) use ($response, $jockey, $coach) {
            // dd($competencyAssessment);
            $response->assertStatus(302);
            $response->assertRedirect("/competency-assessment/{$competencyAssessment->id}");
            
            $this->assertEquals($competencyAssessment->summary, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $competencyAssessment) {
                return $job->competencyAssessment->id == $competencyAssessment->id;
            });         
        });
    }

    /** @test */
    public function a_coach_cannot_edit_a_competency_assessment_they_have_not_created()
    {
            
    }
}