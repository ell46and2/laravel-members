<?php

namespace Tests\Unit;

use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use App\Models\RacingExcellenceParticipant;
use App\Models\SeriesType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $jockeyParticipants = $racingExcellence->jockeys;
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
}