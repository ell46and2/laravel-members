<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
    public function activity_has_a_coach()
    {
        $coach = factory(User::class)->create();

        $activity = factory(Activity::class)->create([
        	'coach_id' => $coach->id
        ]);

        $activitysCoach = $activity->coach;

        $this->assertTrue($activitysCoach->is($coach));
    }

    /** @test */
    public function a_jockey_can_be_added_to_an_activity()
    {
    	$jockey = factory(User::class)->create();

        $activity = factory(Activity::class)->create();

        $activity->addJockey($jockey);

        $addedJockey = $activity->jockeys()->first();

        $this->assertTrue($addedJockey->is($jockey));
    }

    /** @test */
    public function a_group_of_jockeys_can_be_added_to_an_activity()
    {
    	$jockey1 = factory(User::class)->create();
        $jockey2 = factory(User::class)->create();
        $jockey3 = factory(User::class)->create();
        $jockeyNotAddedToActivity = factory(User::class)->create();

        $activity = factory(Activity::class)->create();

        $activity->addJockeysById([$jockey1->id, $jockey2->id, $jockey3->id]);

       	$addedJockeys = $activity->jockeys()->get();

       	$this->assertEquals($addedJockeys->count(), 3);

        $addedJockeys->assertContains($jockey1);
        $addedJockeys->assertContains($jockey2);
        $addedJockeys->assertContains($jockey3);
        $addedJockeys->assertNotContains($jockeyNotAddedToActivity);
    }

    /** @test */
    public function a_subsection_of_jockeys_can_be_removed_from_an_activity()
    {
        // $activity->removeJockeysById
    }

    /** @test */
    public function a_jockey_cannot_be_added_twice_to_an_activity()
    {
    	$jockey = factory(User::class)->create();

        $activity = factory(Activity::class)->create();

        $activity->addJockey($jockey);
        $this->assertEquals($activity->jockeys()->count(), 1);

        $activity->addJockey($jockey);

        $this->assertEquals($activity->jockeys()->count(), 1);
        $this->assertTrue($activity->jockeys()->first()->is($jockey));

    }
}