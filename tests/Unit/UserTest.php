<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Country;
use App\Models\Jockey;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function can_get_the_admin_users()
    {
    	factory(Jockey::class, 5)->create();
      factory(Coach::class, 5)->create();

      $adminUser = factory(Admin::class)->create();

      $this->assertEquals($adminUser->id, Admin::first()->id);
    }

    /** @test */
    public function can_get_users_full_name()
    {
        $user = factory(Jockey::class)->create([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        ]);

        $this->assertEquals($user->full_name, 'Jane Doe');	
    }

    /** @test */
    public function can_get_jockeys_coaches()
    {
    	$jockey = factory(Jockey::class)->create([
    		'first_name' => 'Ell'
    	]);

    	$coach1 = factory(Coach::class)->create();
    	$coach2 = factory(Coach::class)->create();
    	$coach3 = factory(Coach::class)->create();
    	$notJockeysCoach = factory(Coach::class)->create();
    	$adminUser = factory(Admin::class)->create();

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
        $coach = factory(Coach::class)->create();

       	$jockey1 = factory(Jockey::class)->create();
       	$jockey2 = factory(Jockey::class)->create();
       	$jockey3 = factory(Jockey::class)->create();
       	$notCoachesJockey = factory(Jockey::class)->create();

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
        $coach1 = factory(Coach::class)->create();
        $coach2 = factory(Coach::class)->create();

        $jockey1 = factory(User::class)->create();
       	$jockey2 = factory(User::class)->create();

       	$admin = factory(Admin::class)->create();

       	$coaches = Coach::get();

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
        $coach = factory(Coach::class)->create();

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
    	$jockey = factory(Jockey::class)->create();
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

    /** @test */
    public function total_hours_spent_training_for_jockey_for_current_month()
    {
      // NEED TO ADD GROUP ACTIVITIES
      // if activity is a group activity duration/number_of_jockeys rounded down to nearest whole number.

        $faker = Factory::create();

        $jockey = factory(Jockey::class)->states('approved')->create();
        $coach1 = factory(Coach::class)->create();
        $coach2 = factory(Coach::class)->create();
        $differentJockey = factory(Jockey::class)->states('approved')->create();

        // dd($faker->dateTimeThisMonth());

        $activity1 = factory(Activity::class)->create([
          'coach_id' => $coach1->id,
          'duration' => '30',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(30),
        ]);

        $activity2 = factory(Activity::class)->create([
          'coach_id' => $coach2->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activity3 = factory(Activity::class)->create([
          'coach_id' => $coach2->id,
          'duration' => '30',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(30),
        ]);

        $activityNotInCurrentMonth = factory(Activity::class)->create([
          'coach_id' => $coach1->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth()->subMonth(2), // In current month
          'end' => Carbon::now()->startOfMonth()->subMonth(2)->addMinutes(60),
        ]);
        
        $activityInCurrentMonthButFuture = factory(Activity::class)->create([
          'coach_id' => $coach2->id,
          'duration' => '60',
          'start' => Carbon::now()->addMinutes(60), // In current month
          'end' => Carbon::now()->addMinutes(120),
        ]);

        $activityNotWithCoach = factory(Activity::class)->create([
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activityWithDifferentJockey = factory(Activity::class)->create([
          'coach_id' => $coach1->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $groupActivity = factory(Activity::class)->create([
          'coach_id' => $coach1->id,
          'duration' => '15',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activity1->addJockey($jockey);
        $activity2->addJockey($jockey);
        $activity3->addJockey($jockey);
        $activityNotInCurrentMonth->addJockey($jockey);
        $activityInCurrentMonthButFuture->addJockey($jockey);
        $activityWithDifferentJockey->addJockey($differentJockey);
        // dd($coach->activities);
        $groupActivity->addJockeysById([$jockey->id, $differentJockey->id]);

        $this->assertEquals($jockey->trainingTimeThisMonth(), 2.12);
    }

    /** @test */
    public function hours_spent_training_with_a_coach_for_current_month() // doesn't need to include Racing Excellence.
    {
        $faker = Factory::create();

        $jockey = factory(Jockey::class)->states('approved')->create();
        $coach = factory(Coach::class)->create();
        $differentJockey = factory(Jockey::class)->states('approved')->create();

        // dd($faker->dateTimeThisMonth());

        $activity1 = factory(Activity::class)->create([
          'coach_id' => $coach->id,
          'duration' => '30',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(30),
        ]);

        $activity2 = factory(Activity::class)->create([
          'coach_id' => $coach->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activityNotInCurrentMonth = factory(Activity::class)->create([
          'coach_id' => $coach->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth()->subMonth(2), // In current month
          'end' => Carbon::now()->startOfMonth()->subMonth(2)->addMinutes(60),
        ]);
        
        $activityInCurrentMonthButFuture = factory(Activity::class)->create([
          'coach_id' => $coach->id,
          'duration' => '60',
          'start' => Carbon::now()->addMinutes(60), // In current month
          'end' => Carbon::now()->addMinutes(120),
        ]);

        $activityNotWithCoach = factory(Activity::class)->create([
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activityWithDifferentJockey = factory(Activity::class)->create([
          'coach_id' => $coach->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(), // In current month
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activity1->addJockey($jockey);
        $activity2->addJockey($jockey);
        $activityNotInCurrentMonth->addJockey($jockey);
        $activityInCurrentMonthButFuture->addJockey($jockey);
        $activityWithDifferentJockey->addJockey($differentJockey);
        // dd($coach->activities);

        $this->assertEquals($coach->trainingTimeWithJockeyThisMonth($jockey->id), 1.5);
        // dd($coach->trainingTimeWithJockeyThisMonth($jockey->id));
    }

}
