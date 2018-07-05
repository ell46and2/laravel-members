<?php

namespace Tests\Feature\Coach;

use App\Jobs\RacingExcellence\Results\NotifyAllRacingResults;
use App\Jobs\RacingExcellence\Results\NotifyRacingResultUpdated;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use App\Models\RacingExcellenceParticipant;
use App\Models\SeriesType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RacingExcellenceTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function on_coach_viewing_RE_result_page_the_view_receives_a_RE_resource()
    {
    	// on a coach viewing the RE results page the view will get passed a RE resouce containing
    	// - divisionResource and that containing participantResouce
    	// that will be passed as a prop to the racing-excellence-results vue component
    	
    	$coach = factory(Coach::class)->create();

        $series = SeriesType::where('total_just_from_place', false)->first();
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $coach->id
	    ]);

	    $division1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $division2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division1->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $participant2 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division1->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->get("/racing-excellence/{$racingExcellence->id}/results");

	    $this->assertEquals(2, $response->data('raceResource')->divisions->count());
	    $this->assertEquals($division1->id, $response->data('raceResource')->divisions->first()->id);

	    $this->assertEquals(2, $response->data('raceResource')->divisions->first()->participants->count());
	    $this->assertEquals($participant1->id, $response->data('raceResource')->divisions->first()->participants->first()->id);
	    $this->assertEquals($participant1->full_name, $response->data('raceResource')->divisions->first()->participants->first()->name);
    }

    /** @test */
    public function can_add_results_and_feedback_for_a_participant_to_a_racing_excellence_if_they_are_the_assigned_coach()
    {
        $coach = factory(Coach::class)->create();

        $series = SeriesType::where('total_just_from_place', false)->first();
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $coach->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant1->id}", [
	    	'place' => 1,
	    	'presentation_points' => 0,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => 'Participants feedback.',
        ]);

        tap($participant1->fresh(), function($participant1) use ($response) {
            // $response->assertStatus(302);
            // Add tests for the Resource returned
           
            $this->assertEquals(1, $participant1->place);
            $this->assertEquals(0, $participant1->presentation_points);
            $this->assertEquals(1, $participant1->professionalism_points);
            $this->assertEquals(2, $participant1->coursewalk_points);
            $this->assertEquals(2, $participant1->riding_points);
            $this->assertEquals(10, $participant1->total_points);
            $this->assertEquals('Participants feedback.', $participant1->feedback);
        });
    }

    /** @test */
    public function cannot_add_results_and_feedback_for_a_participant_to_a_racing_excellence_if_they_are_not_the_assigned_coach()
    {
        $coach = factory(Coach::class)->create();
        $otherCoach = factory(Coach::class)->create();

        $series = SeriesType::where('total_just_from_place', false)->first();
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $otherCoach->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant1->id}", [
	    	'place' => 1,
	    	'presentation_points' => 0,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => 'Participants feedback.',
        ]);

        tap($participant1->fresh(), function($participant1) use ($response) {
            // $response->assertStatus(302);
            // Add tests for the Resource returned
           
            $this->assertEquals(null, $participant1->place);
            $this->assertEquals(null, $participant1->presentation_points);
            $this->assertEquals(null, $participant1->professionalism_points);
            $this->assertEquals(null, $participant1->coursewalk_points);
            $this->assertEquals(null, $participant1->riding_points);
            $this->assertEquals(null, $participant1->total_points);
            $this->assertEquals(null, $participant1->feedback);
        });
    }

    /** @test */
    public function total_points_calculation_is_based_just_on_the_place_for_total_just_from_place_series()
    {    
        $coach = factory(Coach::class)->create();

        $series = SeriesType::where('total_just_from_place', true)->first(); // Salisbury
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $coach->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant1->id}", [
	    	'place' => 2,
	    	'presentation_points' => 0,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => 'Participants feedback.',
        ]);

        tap($participant1->fresh(), function($participant1) use ($response) {
            // $response->assertStatus(302);
            // Add tests for the Resource returned
         
           
            $this->assertEquals(2, $participant1->place);
            $this->assertEquals(0, $participant1->presentation_points);
            $this->assertEquals(1, $participant1->professionalism_points);
            $this->assertEquals(2, $participant1->coursewalk_points);
            $this->assertEquals(2, $participant1->riding_points);
            $this->assertEquals(6, $participant1->total_points);
            $this->assertEquals('Participants feedback.', $participant1->feedback);
        });
    }

    /** @test */
    public function points_must_be_between_0_to_2()
    {
        $coach = factory(Coach::class)->create();

        $series = SeriesType::where('total_just_from_place', false)->first();
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $coach->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant1->id}", [
	    	'place' => 1,
	    	'presentation_points' => 3,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => 'Participants feedback.',
        ]);

        tap($participant1->fresh(), function($participant1) use ($response) {
            // $response->assertStatus(302);
            // Add tests for the Resource returned
           
            $this->assertEquals(null, $participant1->place);
            $this->assertEquals(null, $participant1->presentation_points);
            $this->assertEquals(null, $participant1->professionalism_points);
            $this->assertEquals(null, $participant1->coursewalk_points);
            $this->assertEquals(null, $participant1->riding_points);
            $this->assertEquals(null, $participant1->total_points);
            $this->assertEquals(null, $participant1->feedback);
        });
    }

    /** @test */
    public function can_set_a_participant_as_did_not_finish()
    {
    	$coach = factory(Coach::class)->create();

        $series = SeriesType::where('total_just_from_place', false)->first();
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $coach->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant1->id}", [
	    	'place' => 'dnf',
	    	'presentation_points' => 0,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => 'Participants feedback.',
        ]);

        tap($participant1->fresh(), function($participant1) use ($response) {
            // $response->assertStatus(302);
            // Add tests for the Resource returned
           
            $this->assertEquals(null, $participant1->place);
            $this->assertEquals(0, $participant1->presentation_points);
            $this->assertEquals(1, $participant1->professionalism_points);
            $this->assertEquals(2, $participant1->coursewalk_points);
            $this->assertEquals(2, $participant1->riding_points);
            $this->assertEquals(5, $participant1->total_points);
            $this->assertFalse($participant1->completed_race);
            $this->assertEquals('Participants feedback.', $participant1->feedback);
        });
    }

    /** @test */
    public function when_all_participants_results_are_entered()
    {	
    	Queue::fake();

        // RE set as completed
        // notifications job dispatched
        $coach = factory(Coach::class)->create();

        $series = SeriesType::first();
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $coach->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $participant2 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant1->id}", [
	    	'place' => 1,
	    	'presentation_points' => 0,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => 'Participants feedback.',
        ]);

        tap($participant1->fresh(), function($participant1) use ($response, $racingExcellence) {
            Queue::assertNotPushed(NotifyAllRacingResults::class);
            Queue::assertNotPushed(NotifyRacingResultUpdated::class);

            $this->assertFalse($racingExcellence->fresh()->completed);
        });

        $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant2->id}", [
	    	'place' => 2,
	    	'presentation_points' => 0,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => 'Participants feedback.',
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
            Queue::assertPushed(NotifyAllRacingResults::class, function($job) use ($response, $racingExcellence) {
                return $job->racingExcellence->id == $racingExcellence->id;
            });
            Queue::assertNotPushed(NotifyRacingResultUpdated::class);

            $this->assertTrue($racingExcellence->completed);        
        });
    }

    /** @test */
    public function if_race_is_completed_and_results_are_posted_notify_that_jockey_of_update()
    {
        Queue::fake();

        // RE set as completed
        // notifications job dispatched
        $coach = factory(Coach::class)->create();

        $series = SeriesType::first();
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $coach->id,
	    	'completed' => true,
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant1->id}", [
	    	'place' => 1,
	    	'presentation_points' => 0,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => 'Participants feedback.',
        ]);

        tap($participant1->fresh(), function($participant1) use ($response, $racingExcellence) {
            Queue::assertPushed(NotifyAllRacingResults::class, function($job) use ($racingExcellence) {
                return $job->racingExcellence->id == $racingExcellence->id;
            });

            Queue::assertPushed(NotifyRacingResultUpdated::class, function($job) use ($participant1) {
                return $job->participant->id == $participant1->id;
            });

            $this->assertTrue($racingExcellence->fresh()->completed);
        });
    }

    /** @test */
    public function feedback_is_always_optional()
    {
     	$coach = factory(Coach::class)->create();

        $series = SeriesType::where('total_just_from_place', false)->first();
        
        $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id,
	    	'coach_id' => $coach->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        }
	    ]);

	    $response = $this->actingAs($coach)->put("/racing-excellence/participant/{$participant1->id}", [
	    	'place' => 1,
	    	'presentation_points' => 0,
	    	'professionalism_points' => 1,
	    	'coursewalk_points' => 2,
	    	'riding_points' => 2,
	    	'feedback' => '',
        ]);

        tap($participant1->fresh(), function($participant1) use ($response) {
            // $response->assertStatus(302);
            // Add tests for the Resource returned
           
            $this->assertEquals(1, $participant1->place);
            $this->assertEquals(0, $participant1->presentation_points);
            $this->assertEquals(1, $participant1->professionalism_points);
            $this->assertEquals(2, $participant1->coursewalk_points);
            $this->assertEquals(2, $participant1->riding_points);
            $this->assertEquals(10, $participant1->total_points);
            $this->assertEquals(null, $participant1->feedback);

            // NOTE: Jockey is notified that their results have been added.
        });
            
    }

    /** @test */
    public function place_and_points_required_for_normal_series()
    {
        	
    }

	/** @test */
	public function just_place_required_for_total_just_from_place_series()
	{
	       	
	}


    // all points and place required for normal series
    // only place required for Salisbury (where series->total_just_from_place = true)
    // must be the assigned coach or an admin - add policy.
    
    // points can only be 0, 1 or 2
}