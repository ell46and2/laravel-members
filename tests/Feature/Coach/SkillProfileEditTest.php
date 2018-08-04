<?php

namespace Tests\Feature\Coach;

use App\Jobs\SkillProfile\UpdatedNotifyJockey;
use App\Models\Coach;
use App\Models\SkillProfile;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SkillProfileEditTest extends TestCase
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
            'whip_rating' => 5,
            'whip_observation' => 'New racing knowledge observation',
            'professionalism_rating' => 5,
            'professionalism_observation' => 'New professionalism observation',
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
            'whip_rating' => 1,
            'whip_observation' => 'racing knowledge observation',
            'professionalism_rating' => 1,
            'professionalism_observation' => 'professionalism observation',
            'summary' => 'summary',
        ], $overrides);
    }

    /** @test */
    public function a_coach_can_edit_a_skills_profile_they_have_created()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $skillProfile = factory(SkillProfile::class)->create([
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
            'whip_rating' => 1,
            'whip_observation' => 'racing knowledge observation',
            'professionalism_rating' => 1,
            'professionalism_observation' => 'professionalism observation',
            'summary' => 'summary',
        ]);

        $response = $this->actingAs($coach)
            ->from("/skills-profile/{$skillProfile->id}/edit")
            ->put("/skills-profile/{$skillProfile->id}/update", [
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
                'whip_rating' => 5,
                'whip_observation' => 'New racing knowledge observation',
                'professionalism_rating' => 5,
                'professionalism_observation' => 'New professionalism observation',
                'summary' => 'New summary'
            ]);

        tap($skillProfile->fresh(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals(Carbon::parse($skillProfile->start), Carbon::parse('02/05/2019 18:00'));
            $this->assertEquals($skillProfile->riding_rating, 5);
            $this->assertEquals($skillProfile->riding_observation, 'New riding observation');
            $this->assertEquals($skillProfile->fitness_rating, 5);
            $this->assertEquals($skillProfile->fitness_observation, 'New fitness observation');
            $this->assertEquals($skillProfile->simulator_rating, 5);
            $this->assertEquals($skillProfile->simulator_observation, 'New simulator observation');
            $this->assertEquals($skillProfile->race_riding_skills_rating, 5);
            $this->assertEquals($skillProfile->race_riding_skills_observation, 'New race riding skills observation');
            $this->assertEquals($skillProfile->weight_rating, 5);
            $this->assertEquals($skillProfile->weight_observation, 'New weight observation');
            $this->assertEquals($skillProfile->communication_rating, 5);
            $this->assertEquals($skillProfile->communication_observation, 'New communication observation');
            $this->assertEquals($skillProfile->whip_rating, 5);
            $this->assertEquals($skillProfile->whip_observation, 'New racing knowledge observation');
            $this->assertEquals($skillProfile->professionalism_rating, 5);
            $this->assertEquals($skillProfile->professionalism_observation, 'New professionalism observation');
            $this->assertEquals($skillProfile->summary, 'New summary');

            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });
            
        });
    }

    /** @test */
    public function start_date_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)
            ->from("/skills-profile/{$skillProfile->id}/edit")
            ->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
                'start_date' => ''
            ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('start_date');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function start_date_must_be_a_valid_date()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)
            ->from("/skills-profile/{$skillProfile->id}/edit")
            ->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
                'start_date' => 'not-a-valid-date'
            ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('start_date');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function start_time_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)
            ->from("/skills-profile/{$skillProfile->id}/edit")
            ->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
                'start_time' => ''
            ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('start_time');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function start_time_must_be_a_valid_time()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)
            ->from("/skills-profile/{$skillProfile->id}/edit")
            ->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
                'start_time' => 'not-a-time'
            ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('start_time');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'riding_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'riding_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'riding_rating' => -1,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function riding_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'riding_rating' => 6,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function riding_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'riding_rating' => 2.25,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('riding_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function riding_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'riding_observation' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->riding_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function fitness_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'fitness_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function fitness_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'fitness_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function fitness_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'fitness_rating' => -1,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function fitness_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'fitness_rating' => 6,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function fitness_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'fitness_rating' => 2.25,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function fitness_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'fitness_observation' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->fitness_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function simulator_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'simulator_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function simulator_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'simulator_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function simulator_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'simulator_rating' => -1,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function simulator_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'simulator_rating' => 6,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function simulator_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'simulator_rating' => 2.25,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function simulator_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'simulator_observation' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->simulator_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function race_riding_skills_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'race_riding_skills_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function race_riding_skills_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'race_riding_skills_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function race_riding_skills_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'race_riding_skills_rating' => -1,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function race_riding_skills_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'race_riding_skills_rating' => 6,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function race_riding_skills_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'race_riding_skills_rating' => 2.25,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function race_riding_skills_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'race_riding_skills_observation' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->race_riding_skills_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function weight_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'weight_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function weight_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'weight_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function weight_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'weight_rating' => -1,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function weight_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'weight_rating' => 6,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function weight_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'weight_rating' => 2.25,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('weight_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function weight_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'weight_observation' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->weight_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function communication_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'communication_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function communication_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'communication_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function communication_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'communication_rating' => -1,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function communication_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'communication_rating' => 6,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function communication_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'communication_rating' => 2.25,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('communication_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function communication_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'communication_observation' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->communication_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function whip_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'whip_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('whip_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function whip_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'whip_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('whip_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function whip_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'whip_rating' => -1,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('whip_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function whip_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'whip_rating' => 6,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('whip_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function whip_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'whip_rating' => 2.25,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('whip_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function whip_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'whip_observation' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->whip_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function professionalism_rating_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'professionalism_rating' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function professionalism_rating_must_be_a_float()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'professionalism_rating' => 'not-an-float',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class);
    }

    /** @test */
    public function professionalism_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'professionalism_rating' => -1,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function professionalism_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'professionalism_rating' => 6,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function professionalism_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'professionalism_rating' => 2.25,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect("/skills-profile/{$skillProfile->id}/edit");
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertArraySubset($this->oldAttributes(), $skillProfile->fresh()->getAttributes());
        
        Queue::assertNotPushed(UpdatedNotifyJockey::class); 
    }

    /** @test */
    public function professionalism_observation_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'professionalism_observation' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->professionalism_observation, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function summary_is_optional()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $skillProfile = factory(SkillProfile::class)->create($this->oldAttributes([
            'jockey_id' => $jockey->id
        ]));

        $response = $this->actingAs($coach)->from("/skills-profile/{$skillProfile->id}/edit")->put("/skills-profile/{$skillProfile->id}/update", $this->validParams([
            'summary' => '',
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
            // dd($skillProfile);
            $response->assertStatus(302);
            $response->assertRedirect("/skills-profile/{$skillProfile->id}");
            
            $this->assertEquals($skillProfile->summary, '');
            
            // Notify Jockey
            Queue::assertPushed(UpdatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });         
        });
    }

    /** @test */
    public function a_coach_cannot_edit_a_skills_profile_they_have_not_created()
    {
            
    }
}