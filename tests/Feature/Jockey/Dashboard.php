<?php

namespace Tests\Feature\Jockey;

use App\Models\Activity;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_jockey_can_view_their_next_upcoming_activities_with_a_max_of_10()
    {
        // with id, type, date, time, location
        
        $jockey = factory(Jockey::class)->states('approved')->create();

        $upcomingActivity1 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(2),
            'duration' => '30',
            'end' => Carbon::now()->addDays(2),
        ]);
        $upcomingActivity1->addJockey($jockey);

        $pastActivity = factory(Activity::class)->create([
            'start' => Carbon::now()->subDays(1),
            'duration' => '30',
            'end' => Carbon::now()->subDays(1),
        ]);

        $pastActivity->addJockey($jockey);

        $upcomingActivity2 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(1),
            'duration' => '30',
            'end' => Carbon::now()->addDays(1),
        ]);
        $upcomingActivity2->addJockey($jockey);

        $upcomingActivity3 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(3),
            'duration' => '30',
            'end' => Carbon::now()->addDays(3),
        ]);
        $upcomingActivity3->addJockey($jockey);

        $upcomingActivity4 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(10),
            'duration' => '30',
            'end' => Carbon::now()->addDays(10),
        ]);
        $upcomingActivity4->addJockey($jockey);

        $upcomingActivity5 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(8),
            'duration' => '30',
            'end' => Carbon::now()->addDays(8),
        ]);
        $upcomingActivity5->addJockey($jockey);

        $upcomingActivity6 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(9),
            'duration' => '30',
            'end' => Carbon::now()->addDays(9),
        ]);
        $upcomingActivity6->addJockey($jockey);

        $upcomingActivity7 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(6),
            'duration' => '30',
            'end' => Carbon::now()->addDays(6),
        ]);
        $upcomingActivity7->addJockey($jockey);

        $upcomingActivity8 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(11),
            'duration' => '30',
            'end' => Carbon::now()->addDays(11),
        ]);
        $upcomingActivity8->addJockey($jockey);

        $upcomingActivity9 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(15),
            'duration' => '30',
            'end' => Carbon::now()->addDays(15),
        ]);
        $upcomingActivity9->addJockey($jockey);

        $upcomingActivity10 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(4),
            'duration' => '30',
            'end' => Carbon::now()->addDays(4),
        ]);
        $upcomingActivity10->addJockey($jockey);

        $upcomingActivity11 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(5),
            'duration' => '30',
            'end' => Carbon::now()->addDays(5),
        ]);
        $upcomingActivity11->addJockey($jockey);

        $upcomingActivities = $jockey->upcomingActivities->take(10);

        $this->assertEquals($upcomingActivities->count(), 10);

        $this->assertTrue($upcomingActivities->values()[0]->is($upcomingActivity2));
        $this->assertTrue($upcomingActivities->values()[1]->is($upcomingActivity1));
        $this->assertTrue($upcomingActivities->values()[2]->is($upcomingActivity3));
        $this->assertTrue($upcomingActivities->values()[3]->is($upcomingActivity10));
        $this->assertTrue($upcomingActivities->values()[4]->is($upcomingActivity11));
        $this->assertTrue($upcomingActivities->values()[5]->is($upcomingActivity7));
        $this->assertTrue($upcomingActivities->values()[6]->is($upcomingActivity5));
        $this->assertTrue($upcomingActivities->values()[7]->is($upcomingActivity6));
        $this->assertTrue($upcomingActivities->values()[8]->is($upcomingActivity4));
        $this->assertTrue($upcomingActivities->values()[9]->is($upcomingActivity8));
    }

    /** @test */
    public function a_jockey_will_see_an_appropriate_message_if_no_upcoming_activities()
    {

    }

    /** @test */
    public function a_jockey_can_view_their_recent_activities_with_a_max_of_10()
    {
        // with id, type, date, time, location
    }

    /** @test */
    public function a_jockey_will_see_an_appropriate_message_if_no_recent_activities()
    {
       
    }

    /** @test */
    public function can_see_total_number_of_hours_training_in_current_month()  // check its current month
    {

    }

    /** @test */
    public function can_see_total_number_of_race_wins() // Data from racing post API
    {
            
    }

    /** @test */
    public function can_see_total_number_of_races() // Data from racing post API
    {
            
    }

    /** @test */
    public function can_see_a_list_of_their_coaches_with_the_total_number_of_hours_of_coaching_with_that_coach_in_current_month()
    {
            
    }

    /** @test */
    public function if_not_coaches_currently_assigned_will_see_appropriate_message()
    {
            
    }

    /** @test */
    public function can_view_their_unread_notifications()
    {
            
    }

    /** @test */
    public function can_see_a_badge_with_the_number_of_unread_notifications()
    {
            
    }

    // Need to add Competency Assessment - graph ?
    
    // Need to add PDP - is it just a stock image?
}