<?php

namespace Tests\Feature\Coach;

use App\Models\Coach;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use App\Models\RacingExcellenceParticipant;
use App\Models\SeriesType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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

	    $response = $this->actingAs($coach)->get("/racing-excellence/{$racingExcellence->id}/results");

	    dd($response);
    }

    /** @test */
    public function can_add_results_and_feedback_for_a_particpant_to_a_racing_excellence_if_they_are_the_assigned_coach()
    {
        // The 'total' field gets calculated by an observer
        // The total is different for salisbury series
        
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
            
	    // put request to racing-excellence/participant/$particpant->id
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
	    	'presentation_points' => 99,
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


    // all points and place required for normal series
    // only place required for Salisbury (where series->total_just_from_place = true)
    // must be the assigned coach or an admin - add policy.
    
    // points can only be 0, 1 or 2
}