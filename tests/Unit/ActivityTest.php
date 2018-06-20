<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
    public function activity_has_a_coach()
    {
        $coach = factory(Coach::class)->create();

        $activity = factory(Activity::class)->create([
        	'coach_id' => $coach->id
        ]);

        $activitysCoach = $activity->coach;

        $this->assertTrue($activitysCoach->is($coach));
    }

    /** @test */
    public function a_jockey_can_be_added_to_an_activity()
    {
    	$jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create();

        $activity->addJockey($jockey);

        $addedJockey = $activity->jockeys()->first();

        $this->assertTrue($addedJockey->is($jockey));
    }

    /** @test */
    public function a_group_of_jockeys_can_be_added_to_an_activity()
    {
    	$jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();
        $jockeyNotAddedToActivity = factory(Jockey::class)->create();

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
        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();
        $jockeyToBeRemoved = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create();

        $activity->addJockeysById([$jockey1->id, $jockey2->id, $jockey3->id, $jockeyToBeRemoved->id]);

        $addedJockeys = $activity->jockeys()->get();

        $this->assertEquals($addedJockeys->count(), 4);

        $addedJockeys->assertContains($jockey1);
        $addedJockeys->assertContains($jockey2);
        $addedJockeys->assertContains($jockey3);
        $addedJockeys->assertContains($jockeyToBeRemoved);

        // Excluding jockey to be removed - as we're using sync() any jockey not in the array will be removed.
        $activity->addJockeysById([$jockey1->id, $jockey2->id, $jockey3->id]);

        $newAddedJockeys = $activity->jockeys()->get();

        $this->assertEquals($newAddedJockeys->count(), 3);

        $newAddedJockeys->assertContains($jockey1);
        $newAddedJockeys->assertContains($jockey2);
        $newAddedJockeys->assertContains($jockey3);
        $newAddedJockeys->assertNotContains($jockeyToBeRemoved);
    }

    /** @test */
    public function a_jockey_cannot_be_added_twice_to_an_activity()
    {
    	$jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create();

        $activity->addJockey($jockey);
        $this->assertEquals($activity->jockeys()->count(), 1);

        $activity->addJockey($jockey);

        $this->assertEquals($activity->jockeys()->count(), 1);
        $this->assertTrue($activity->jockeys()->first()->is($jockey));

    }

    /** @test */
    public function a_jockey_can_view_their_upcoming_activities()
    {
    	$jockey = factory(Jockey::class)->create();

    	$activity1 = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-11-06 1:00pm')
    	]);
    	$activity2 = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-11-06 2:00pm')
    	]);
    	$activity3 = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-11-06 1:00am')
    	]);
    	$activity4 = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-09-26 1:00am')
    	]);
    	$activityInPast = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('1998-11-06 1:00pm')
    	]);

		$activity1->addJockey($jockey);
		$activity2->addJockey($jockey);
		$activity3->addJockey($jockey);
		$activity4->addJockey($jockey);
		$activityInPast->addJockey($jockey);
		
		$upcomingActivities = $jockey->upcomingActivities()->get();

		$this->assertEquals($jockey->upcomingActivities()->count(), 4);

		$upcomingActivities->assertContains($activity1);
		$upcomingActivities->assertContains($activity2);
		$upcomingActivities->assertContains($activity3);
		$upcomingActivities->assertContains($activity4);
		$upcomingActivities->assertNotContains($activityInPast);

		// check order is 'asc' from 'end' date.
		$upcomingActivities->assertEquals([
			$activity4,
			$activity3,
			$activity1,
			$activity2
		]);
    }

     /** @test */
    public function a_jockey_can_view_their_recent_activities()
    {
        $jockey = factory(Jockey::class)->create();

    	$activity1 = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-05-06 1:00pm')
    	]);
    	$activity2 = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-05-06 2:00pm')
    	]);
    	$activity3 = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-04-05 1:00am')
    	]);
    	$activity4 = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-02-26 1:00am')
    	]);
    	$activityInFuture = factory(Activity::class)->create([
    		'duration' => 30,
    		'start' => Carbon::parse('2018-11-06 1:00pm')
    	]);

		$activity1->addJockey($jockey);
		$activity2->addJockey($jockey);
		$activity3->addJockey($jockey);
		$activity4->addJockey($jockey);
		$activityInFuture->addJockey($jockey);
		
		$recentActivities = $jockey->recentActivities()->get();

		$this->assertEquals($jockey->recentActivities()->count(), 4);

		$recentActivities->assertContains($activity1);
		$recentActivities->assertContains($activity2);
		$recentActivities->assertContains($activity3);
		$recentActivities->assertContains($activity4);
		$recentActivities->assertNotContains($activityInFuture);

		// check order is 'desc' from 'end' date.
		$recentActivities->assertEquals([
			$activity2,
			$activity1,
			$activity3,
			$activity4
		]);	
    }

    /** @test */
    public function has_relationship_with_user_returns_true_if_user_is_the_assigned_coach()
    {
        $coach = factory(Coach::class)->create();
        $coachAssignedToOtherActivity = factory(Coach::class)->create();

        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        $otherActivity = factory(Activity::class)->create([
            'coach_id' => $coachAssignedToOtherActivity->id
        ]);

        $this->assertTrue($activity->isAssignedToUser($coach));
        $this->assertFalse($activity->isAssignedToUser($coachAssignedToOtherActivity));
    }

    /** @test */
    public function is_assigned_to_user_returns_true_if_user_is_an_assigned_jockey()
    {
        $jockey = factory(Jockey::class)->create();
        $jockeyAssignedToOtherActivity = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create();
        $activity->addJockey($jockey);

        $otherActivity = factory(Activity::class)->create();
        $otherActivity->addJockey($jockeyAssignedToOtherActivity);

        $this->assertTrue($activity->isAssignedToUser($jockey));
        $this->assertFalse($activity->isAssignedToUser($jockeyAssignedToOtherActivity));
    }

    /** @test */
    public function is_assigned_to_user_returns_false_if_user_is_an_admin()
    {
        $admin = factory(Admin::class)->create();

        $activity = factory(Activity::class)->create();

        $this->assertFalse($activity->isAssignedToUser($admin));
    }

    /** @test */
    public function can_see_number_of_unread_comments_excluding_their_own()
    {
            
    }

    /** @test */
    public function can_mark_a_comment_as_read()
    {
        
    }

    /** @test */
    public function can_add_a_video_as_an_attachment_to_a_comment()
    {
            
    }

    /** @test */
    public function can_add_an_image_as_an_attachment_to_a_comment()
    {
            
    }
}