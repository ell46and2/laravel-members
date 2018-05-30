<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
	use DatabaseMigrations;


    /** @test */
    public function can_get_the_admin_user()
    {
    	factory(User::class, 10)->create();

       	$adminUser = factory(User::class)->states('admin')->create();

       	$this->assertEquals($adminUser->id, User::admin()->id);
    }

    /** @test */
    public function can_get_users_full_name()
    {
        $user = factory(User::class)->create([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        ]);

        $this->assertEquals($user->fullName(), 'Jane Doe');	
    }

    /** @test */
    public function can_get_jockeys_coaches()
    {
    	$jockey = factory(User::class)->create([
    		'first_name' => 'Ell'
    	]);

    	$coach1 = factory(User::class)->states('coach')->create();
    	$coach2 = factory(User::class)->states('coach')->create();
    	$coach3 = factory(User::class)->states('coach')->create();
    	$notJockeysCoach = factory(User::class)->states('coach')->create();
    	$adminUser = factory(User::class)->states('admin')->create();

    	$this->assertEquals($jockey->coaches()->count(), 0);

    	$coach1->assignJockey($jockey);
    	$coach2->assignJockey($jockey);
    	$coach3->assignJockey($jockey);

    	$this->assertEquals($jockey->coaches()->count(), 3);

    	$coaches = $jockey->coaches;

    	$coaches->assertContains($coach1);
    	$coaches->assertContains($coach2);
    	$coaches->assertContains($coach3);

    	$coaches->assertNotContains($notJockeysCoach);
    	$coaches->assertNotContains($adminUser);	
    }

    /** @test */
    public function can_get_coaches_jockeys()
    {
        $coach = factory(User::class)->states('coach')->create();

       	$jockey1 = factory(User::class)->create();
       	$jockey2 = factory(User::class)->create();
       	$jockey3 = factory(User::class)->create();
       	$notCoachesJockey = factory(User::class)->create();

       	$this->assertEquals($coach->jockeys()->count(), 0);

       	$coach->assignJockey($jockey1);
       	$coach->assignJockey($jockey2);
       	$coach->assignJockey($jockey3);

       	$this->assertEquals($coach->jockeys()->count(), 3);

       	$jockeys = $coach->jockeys;

       	$jockeys->assertContains($jockey1);
       	$jockeys->assertContains($jockey2);
       	$jockeys->assertContains($jockey3);

       	$jockeys->assertNotContains($notCoachesJockey);
    }

    /** @test */
    public function can_get_all_coaches()
    {
        $coach1 = factory(User::class)->states('coach')->create();
        $coach2 = factory(User::class)->states('coach')->create();

        $jockey1 = factory(User::class)->create();
       	$jockey2 = factory(User::class)->create();

       	$admin = factory(User::class)->states('admin')->create();

       	$coaches = User::getAllCoaches();

       	$this->assertEquals($coaches->count(), 2);

       	$coaches->assertContains($coach1);
       	$coaches->assertContains($coach2);

       	$coaches->assertNotContains($jockey1);
       	$coaches->assertNotContains($jockey2);
       	$coaches->assertNotContains($admin);
    }

    /** @test */
    public function coach_activities_relationship()
    {
        $coach = factory(User::class)->states('coach')->create();

        $this->assertEquals($coach->activities()->count(), 0);

        $activity1 = factory(Activity::class)->create([
        	'coach_id' => $coach->id
        ]);

        $activity2 = factory(Activity::class)->create([
        	'coach_id' => $coach->id
        ]);

        $activityNotForCoach = factory(Activity::class)->create();

        $coachActivities = $coach->activities()->get();

        $this->assertEquals($coachActivities->count(), 2);

        $coachActivities->assertContains($activity1);
       	$coachActivities->assertContains($activity2);

       	$coachActivities->assertNotContains($activityNotForCoach);
    }

    /** @test */
    public function jockey_activities_relationship()
    {
    	$jockey = factory(User::class)->states('jockey')->create();
    	// dd($jockey->activities());
        $this->assertEquals($jockey->activities()->count(), 0);

        $activity1 = factory(Activity::class)->create();
    	$activity2 = factory(Activity::class)->create();
    	$activityNotForJockey = factory(Activity::class)->create();

		$activity1->addJockey($jockey);
		$activity2->addJockey($jockey);

		$jockeyActivities = $jockey->activities()->get();

		$this->assertEquals($jockeyActivities->count(), 2);

		$jockeyActivities->assertContains($activity1);
       	$jockeyActivities->assertContains($activity2);

       	$jockeyActivities->assertNotContains($activityNotForJockey);
    }



}
