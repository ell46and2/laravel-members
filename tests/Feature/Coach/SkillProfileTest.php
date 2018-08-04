<?php

namespace Tests\Feature\Coach;

use App\Jobs\SkillProfile\CreatedNotifyJockey;
use App\Models\Coach;
use App\Models\SkillProfile;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SkillProfileTest extends TestCase
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
            'whip_rating' => 3.5,
            'whip_observation' => 'New whip observation',
            'professionalism_rating' => 5,
            'professionalism_observation' => 'New professionalism observation',
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
            'whip_rating' => 3.5,
            'whip_observation' => 'whip observation',
            'professionalism_rating' => 5,
            'professionalism_observation' => 'professionalism observation',
            'summary' => 'summary',
        ], $overrides);
    }

    /** @test */
    public function a_coach_can_create_a_skills_profile_for_one_of_their_jockeys()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $response = $this->actingAs($coach)->post("/coach/skills-profile", [
        	'jockeys' => [$jockey->id => "on"],
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
            'whip_rating' => 3.5,
            'whip_observation' => 'whip observation',
            'professionalism_rating' => 5,
            'professionalism_observation' => 'professionalism observation',
            'summary' => 'summary',
        ]);

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");

            dd(Carbon::parse($skillProfile->start));
         	
         	$this->assertEquals($skillProfile->coach_id, $coach->id);
         	$this->assertEquals($skillProfile->jockey_id, $jockey->id);
         	$this->assertEquals(Carbon::parse($skillProfile->start),Carbon::createFromFormat('d/m/Y H:i','11/12/2018 13:00'));
         	$this->assertEquals($skillProfile->riding_rating, 0.5);
         	$this->assertEquals($skillProfile->riding_observation, 'riding observation');
         	$this->assertEquals($skillProfile->fitness_rating, 1);
         	$this->assertEquals($skillProfile->fitness_observation, 'fitness observation');
         	$this->assertEquals($skillProfile->simulator_rating, 1.5);
         	$this->assertEquals($skillProfile->simulator_observation, 'simulator observation');
         	$this->assertEquals($skillProfile->race_riding_skills_rating, 2);
         	$this->assertEquals($skillProfile->race_riding_skills_observation, 'race riding skills observation');
         	$this->assertEquals($skillProfile->weight_rating, 2.5);
         	$this->assertEquals($skillProfile->weight_observation, 'weight observation');
         	$this->assertEquals($skillProfile->communication_rating, 3);
         	$this->assertEquals($skillProfile->communication_observation, 'communication observation');
         	$this->assertEquals($skillProfile->whip_rating, 3.5);
         	$this->assertEquals($skillProfile->whip_observation, 'whip observation');
         	$this->assertEquals($skillProfile->professionalism_rating, 5);
         	$this->assertEquals($skillProfile->professionalism_observation, 'professionalism observation');
         	$this->assertEquals($skillProfile->summary, 'summary');

         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'start_date' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function start_date_must_be_a_valid_date()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'start_date' => 'not-a-valid-date',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function start_time_is_required()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'start_time' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('start_time');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function start_time_must_be_a_valid_time()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'start_time' => 'not-a-time',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('start_time');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_is_required()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'riding_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'riding_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function riding_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'riding_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function riding_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'riding_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function riding_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'riding_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('riding_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function riding_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'riding_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->riding_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'fitness_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function fitness_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'fitness_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function fitness_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'fitness_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function fitness_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'fitness_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function fitness_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'fitness_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('fitness_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function fitness_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'fitness_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->fitness_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'simulator_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function simulator_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'simulator_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function simulator_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'simulator_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function simulator_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'simulator_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function simulator_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'simulator_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('simulator_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function simulator_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'simulator_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->simulator_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'race_riding_skills_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function race_riding_skills_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'race_riding_skills_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function race_riding_skills_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'race_riding_skills_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function race_riding_skills_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'race_riding_skills_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function race_riding_skills_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'race_riding_skills_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('race_riding_skills_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function race_riding_skills_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'race_riding_skills_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->race_riding_skills_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'weight_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function weight_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'weight_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function weight_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'weight_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function weight_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'weight_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function weight_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'weight_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('weight_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function weight_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'weight_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->weight_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'communication_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function communication_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'communication_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function communication_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'communication_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function communication_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'communication_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function communication_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'communication_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('communication_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function communication_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'communication_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->communication_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'whip_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('whip_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function whip_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'whip_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('whip_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function whip_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'whip_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('whip_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function whip_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'whip_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('whip_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function whip_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'whip_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('whip_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function whip_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'whip_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->whip_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'professionalism_rating' => '',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function professionalism_rating_must_be_a_float()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'professionalism_rating' => 'not-an-float',
            'jockey_id' => $jockey->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);
    }

    /** @test */
    public function professionalism_rating_cannot_be_a_negative_number()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'professionalism_rating' => -1,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function professionalism_rating_cannot_be_a_greater_than_5()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'professionalism_rating' => 6,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function professionalism_rating_must_be_in_increments_of_zero_point_five()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'professionalism_rating' => 2.25,
            'jockey_id' => $jockey->id,
        ]));

        // dd(SkillProfile::first());

        $response->assertStatus(302);
        $response->assertRedirect('/coach/skills-profile/create');
        $response->assertSessionHasErrors('professionalism_rating');
        $this->assertEquals(0, SkillProfile::count());
        
        Queue::assertNotPushed(CreatedNotifyJockey::class);	
    }

    /** @test */
    public function professionalism_observation_is_optional()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'professionalism_observation' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->professionalism_observation, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
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

        $response = $this->actingAs($coach)->from("/coach/skills-profile/create")->post("/coach/skills-profile", $this->validParams([
            'summary' => '',
            'jockey_id' => $jockey->id,
        ]));

        tap(SkillProfile::first(), function($skillProfile) use ($response, $jockey, $coach) {
        	// dd($skillProfile);
        	$response->assertStatus(302);
         	$response->assertRedirect("/skills-profile/{$skillProfile->id}");
         	
         	$this->assertEquals($skillProfile->summary, '');
         	
         	// Notify Jockey
         	Queue::assertPushed(CreatedNotifyJockey::class, function($job) use ($response, $skillProfile) {
                return $job->skillProfile->id == $skillProfile->id;
            });       	
        });
    }
}