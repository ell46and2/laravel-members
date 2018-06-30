<?php

namespace Tests\Unit;

use App\Jobs\RacingExcellence\NotifyAddedToRacingExcellence;
use App\Jobs\RacingExcellence\NotifyRemovedFromRacingExcellence;
use App\Models\Admin;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use App\Models\RacingExcellenceParticipant;
use App\Models\SeriesType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RacingExcellenceTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_racing_excellence_is_made_up_of_one_or_more_divisions()
	{
	    $racingExcellence = factory(RacingExcellence::class)->create();
	    $division1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);
	    $division2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $divisions = $racingExcellence->divisions;

	    $this->assertEquals($divisions->count(), 2);
	    $divisions->assertContains($division1);
	    $divisions->assertContains($division2);
	}

	/** @test */
	public function a_group_of_jockeys_can_be_added_to_a_division()
	{
		$racingExcellence = factory(RacingExcellence::class)->create();
	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();
        $jockeyNotAddedToDivision = factory(Jockey::class)->create();

	    $division->addJockeysById(collect([$jockey1->id, $jockey2->id, $jockey3->id]));

	    $participants = $division->participants;

	    $this->assertEquals($participants->count(), 3);
	    $participants->pluck('jockey_id')->assertContains($jockey1->id);
	    $participants->pluck('jockey_id')->assertContains($jockey2->id);
	    $participants->pluck('jockey_id')->assertContains($jockey3->id);
	    $participants->pluck('jockey_id')->assertNotContains($jockeyNotAddedToDivision->id);
	}

	/** @test */
	public function a_jockey_can_be_added_to_two_divisions()
	{
		$jockey = factory(Jockey::class)->create();

	    $racingExcellence = factory(RacingExcellence::class)->create();
	    $division1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $division2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $division1->addJockeysById(collect([$jockey->id]));
	    $division2->addJockeysById(collect([$jockey->id]));

	    $this->assertEquals(1, $division1->participants->count());
	    $this->assertEquals($jockey->id, $division1->participants->first()->jockey_id);
	    
	    $this->assertEquals(1, $division2->participants->count());
	    $this->assertEquals($jockey->id, $division2->participants->first()->jockey_id);

	    $this->assertEquals(1, $jockey->racingExcellences->count());
	    $this->assertEquals($racingExcellence->id, $jockey->racingExcellences->first()->id);
	}

	/** @test */
	public function a_participant_who_is_not_on_the_system_can_be_added()
	{
	    $racingExcellence = factory(RacingExcellence::class)->create();
	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $division->addExternalParticipants(collect(['John Doe']));

	    $this->assertEquals($division->participants->count(), 1);
	    $this->assertEquals($division->participants->first()->name, 'John Doe');
	}

	/** @test */
	public function can_get_all_participants_for_a_racing_excellence()
	{
	    $racingExcellence = factory(RacingExcellence::class)->create();
	    $division1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);
	    $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();
        $division1->addJockeysById(collect([$jockey1->id, $jockey2->id, $jockey3->id]));

        $division2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);
	    $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();
        $division2->addJockeysById(collect([$jockey4->id, $jockey5->id]));
        $division2->addExternalParticipants(collect(['John Doe']));

        $participants = $racingExcellence->participants;
        $this->assertEquals($participants->count(), 6);

        // Actual Jockeys (not including external participants).
        $jockeyParticipants = $racingExcellence->jockeyParticipants;
        $this->assertEquals($jockeyParticipants->count(), 5);
	}

	/** @test */
	public function can_calculate_the_participants_points_total_for_standard_series()
	{
	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => 1
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        },
	        'place' => 1,
	        'completed_race' => true,
	        'presentation_points' => 1,
	        'professionalism_points' => 0,
	        'coursewalk_points' => 2,
	        'riding_points' => 2,
	    ]);

	    $this->assertEquals(10, $participant1->calculateTotalPoints());
	}

	/** @test */
	public function can_calculate_the_participants_points_total_for_place_only_series()
	{
	    $salisbury = SeriesType::where('total_just_from_place', true)->first();

	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $salisbury->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        },
	        'place' => 1,
	        'completed_race' => true,
	        'presentation_points' => 0,
	        'professionalism_points' => 0,
	        'coursewalk_points' => 0,
	        'riding_points' => 0,
	    ]);

	    $participant2 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        },
	        'place' => 1,
	        'completed_race' => true,
	        'presentation_points' => 2,
	        'professionalism_points' => 2,
	        'coursewalk_points' => 2,
	        'riding_points' => 2,
	    ]);

	    $participant3 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        },
	        'place' => 6,
	        'completed_race' => true,
	        'presentation_points' => 2,
	        'professionalism_points' => 2,
	        'coursewalk_points' => 2,
	        'riding_points' => 2,
	    ]);

	    $this->assertEquals(10, $participant1->calculateTotalPoints());
	    $this->assertEquals(10, $participant2->calculateTotalPoints());
	    $this->assertEquals(1, $participant3->calculateTotalPoints());
	}

	/** @test */
	public function can_get_the_place_points_for_a_participant()
	{
	    $series = SeriesType::first();

	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'series_id' => $series->id
	    ]);

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        },
	        'place' => 1,
	        'completed_race' => true,
	        'presentation_points' => 0,
	        'professionalism_points' => 0,
	        'coursewalk_points' => 0,
	        'riding_points' => 0,
	    ]);

	    $participant2 = factory(RacingExcellenceParticipant::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id,
	        'division_id' => $division->id,
	        'jockey_id' => function() {
	            return factory(Jockey::class)->create()->id;
	        },
	        'place' => 8,
	        'completed_race' => true,
	        'presentation_points' => 0,
	        'professionalism_points' => 0,
	        'coursewalk_points' => 0,
	        'riding_points' => 0,
	    ]);

	    $this->assertEquals(5, $participant1->placePoints());
	    $this->assertEquals(0, $participant2->placePoints());
	}

	/** @test */
	public function if_participant_did_not_complete_the_race()
	{
	    // completed_race set to false
	    // place points set to 0
	}

	/** @test */
	public function can_add_a_jockey_participant()
	{
		Queue::fake();

		$admin = factory(Admin::class)->create();

		$jockey = factory(Jockey::class)->create();

		$racingExcellence = factory(RacingExcellence::class)->create();

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $response = $this->actingAs($admin)->post("/racing-excellence/{$division->id}/participant/create", [
            'id' => $jockey->id,
        ]);

        $this->assertEquals(1, $racingExcellence->participants->count());

        $this->assertEquals($jockey->id, $racingExcellence->participants->first()->jockey_id);

        // test notification sent
        Queue::assertPushed(NotifyAddedToRacingExcellence::class, function($job) use ($response, $jockey) {
            return $job->participant->jockey_id == $jockey->id;
        });
	}

	/** @test */
	public function if_added_jockey_already_exists_in_another_division_do_not_send_another_notification()
	{
	    Queue::fake();

		$admin = factory(Admin::class)->create();

		$jockey = factory(Jockey::class)->create();

		$racingExcellence = factory(RacingExcellence::class)->create();

	    $division1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $division2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant = factory(RacingExcellenceParticipant::class)->create([
            'racing_excellence_id' => $racingExcellence->id,
            'division_id' => $division1->id,
            'jockey_id' => $jockey->id
        ]);

	    $response = $this->actingAs($admin)->post("/racing-excellence/{$division2->id}/participant/create", [
            'id' => $jockey->id,
        ]);

        $this->assertEquals(2, $racingExcellence->participants->count());
        $this->assertEquals($jockey->id, $division1->participants->first()->jockey_id);
        $this->assertEquals($jockey->id, $division2->participants->first()->jockey_id);

        // test notification sent
        Queue::assertNotPushed(NotifyAddedToRacingExcellence::class);
	}

	/** @test */
	public function can_add_an_external_participant()
	{
		Queue::fake();

		$admin = factory(Admin::class)->create();

		$racingExcellence = factory(RacingExcellence::class)->create();

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $response = $this->actingAs($admin)->post("/racing-excellence/{$division->id}/participant/external-create", [
            'name' => 'John Doe',
        ]);

	    $this->assertEquals(1, $racingExcellence->participants->count());
        $this->assertEquals('John Doe', $racingExcellence->participants->first()->name);

        Queue::assertNotPushed(NotifyAddedToRacingExcellence::class);
	}

	/** @test */
	public function can_remove_a_jockey_participant()
	{
		Queue::fake();

		$admin = factory(Admin::class)->create();
		$jockey = factory(Jockey::class)->create();

		$racingExcellence = factory(RacingExcellence::class)->create();

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant = factory(RacingExcellenceParticipant::class)->create([
            'racing_excellence_id' => $racingExcellence->id,
            'division_id' => $division->id,
            'jockey_id' => $jockey->id
        ]);

        $this->assertEquals(1, $racingExcellence->participants->count());

        $response = $this->actingAs($admin)->delete("/racing-excellence/participant/{$participant->id}");

        $this->assertEquals(0, $racingExcellence->fresh()->participants->count());

        // test notification sent
        Queue::assertPushed(NotifyRemovedFromRacingExcellence::class, function($job) use ($response, $jockey, $racingExcellence) {
            return $job->racingExcellence->id == $racingExcellence->id &&
            	$job->jockeyId = $jockey->id;
        });
	}

	/** @test */
	public function can_remove_an_external_jockey_participant()
	{
	    Queue::fake();

		$admin = factory(Admin::class)->create();

		$racingExcellence = factory(RacingExcellence::class)->create();

	    $division = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant = factory(RacingExcellenceParticipant::class)->create([
            'racing_excellence_id' => $racingExcellence->id,
            'division_id' => $division->id,
            'jockey_id' => '',
            'name' => 'John Doe',
        ]);

        $this->assertEquals(1, $racingExcellence->participants->count());

        $response = $this->actingAs($admin)->delete("/racing-excellence/participant/{$participant->id}");

        $this->assertEquals(0, $racingExcellence->fresh()->participants->count());

        // test notification sent
        Queue::assertNotPushed(NotifyRemovedFromRacingExcellence::class);
	}

	/** @test */
	public function if_removed_jockey_still_exists_in_other_division_do_not_send_notification()
	{
	    Queue::fake();

		$admin = factory(Admin::class)->create();
		$jockey = factory(Jockey::class)->create();

		$racingExcellence = factory(RacingExcellence::class)->create();

	    $division1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $division2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $participant1 = factory(RacingExcellenceParticipant::class)->create([
            'racing_excellence_id' => $racingExcellence->id,
            'division_id' => $division1->id,
            'jockey_id' => $jockey->id
        ]);

        $participant2 = factory(RacingExcellenceParticipant::class)->create([
            'racing_excellence_id' => $racingExcellence->id,
            'division_id' => $division2->id,
            'jockey_id' => $jockey->id
        ]);


        $this->assertEquals(2, $racingExcellence->participants->count());
        $this->assertEquals(2, $racingExcellence->jockeyParticipants->count());

        $response = $this->actingAs($admin)->delete("/racing-excellence/participant/{$participant1->id}");

        $this->assertEquals(1, $racingExcellence->fresh()->participants->count());
        $this->assertEquals(1, $racingExcellence->fresh()->jockeyParticipants->count());

        // test notification sent
        Queue::assertNotPushed(NotifyRemovedFromRacingExcellence::class);	
	}
}