<?php

namespace Tests\Feature\Coach;

use App\Models\Activity;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\Message;
use App\Models\Notification;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DashboardTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function a_coach_can_see_the_days_remaining_to_submit_their_invoice()
    {
        Carbon::setTestNow(Carbon::parse('2018-03-1'));
        $this->assertEquals(daysToSubmitInvoice(), 9);

        Carbon::setTestNow(Carbon::parse('2018-03-10'));
    	$this->assertEquals(daysToSubmitInvoice(), 0);

        Carbon::setTestNow(Carbon::parse('2018-03-11'));
        $this->assertEquals(daysToSubmitInvoice(), 30);
    }

    /** @test */
    public function can_see_number_of_jockeys_they_coach()
    {
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockeyNotAssignedToCoach = factory(Jockey::class)->create();

        $coach->assignJockey($jockey1);
        $coach->assignJockey($jockey2);
        $coach->assignJockey($jockey3);

        $this->assertEquals($coach->numberOfJockeysCoaching(), 3);
    }

    /** @test */
    public function jockeys_are_colour_coded_depending_on_last_activity_with_coach_date()
    {
        /*
            within 2 weeks = green
            2-4 weeks = yellow
            > 4 = red

            if no last activity default to blue
        */
       

       
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();
        $jockey4 = factory(Jockey::class)->create();
        $coach->assignJockey($jockey1);
        $coach->assignJockey($jockey2);
        $coach->assignJockey($jockey3);
        $coach->assignJockey($jockey4);

        $jockey1ActivityA = factory(Activity::class)->create([
            'coach_id' => $coach->id,
            'start' => Carbon::now()->subDays(17)
        ]);

        $jockey1ActivityA->addJockey($jockey1);

        $jockey1ActivityB = factory(Activity::class)->create([
            'coach_id' => $coach->id,
            'start' => Carbon::now()->subDays(9)
        ]);

        $jockey1ActivityB->addJockey($jockey1);

        $jockey1ActivityC = factory(Activity::class)->create([
            'coach_id' => $coach->id,
            'start' => Carbon::now()->addDays(1)
        ]);

        $jockey1ActivityC->addJockey($jockey1);

        $jockey2Activity = factory(Activity::class)->create([
            'coach_id' => $coach->id,
            'start' => Carbon::now()->subDays(15)
        ]);

        $jockey2Activity->addJockey($jockey2);

        $jockey3Activity = factory(Activity::class)->create([
            'coach_id' => $coach->id,
            'start' => Carbon::now()->subDays(30)
        ]);

        $jockey3Activity->addJockey($jockey3);

        $this->assertEquals('green', $coach->lastActivityDateColourCode($jockey1));
        $this->assertEquals('yellow', $coach->lastActivityDateColourCode($jockey2));
        $this->assertEquals('red', $coach->lastActivityDateColourCode($jockey3));
        $this->assertEquals('blue', $coach->lastActivityDateColourCode($jockey4));
    }

    /** @test */
    public function can_see_number_of_activities_in_the_next_7_days()
    {
    	$coach = factory(Coach::class)->create();

    	$activity1 = factory(Activity::class)->create([
        	'coach_id' => $coach->id,
    		'start' => Carbon::now()->addDays(1),
        ]);
        $activity2 = factory(Activity::class)->create([
        	'coach_id' => $coach->id,
    		'start' => Carbon::now()->addDays(3),
        ]);
        $activity3 = factory(Activity::class)->create([
        	'coach_id' => $coach->id,
    		'start' => Carbon::now()->addDays(6),
        ]);
        $activity4 = factory(Activity::class)->create([
        	'coach_id' => $coach->id,
    		'start' => Carbon::now()->addDays(7),
        ]);

        $activityLaterThan7 = factory(Activity::class)->create([
        	'coach_id' => $coach->id,
    		'start' => Carbon::now()->addDays(8),
        ]);

        $activityEarlierThanNow = factory(Activity::class)->create([
        	'coach_id' => $coach->id,
    		'start' => Carbon::now()->subHours(1),
        ]);

        $this->assertEquals($coach->activitiesInNextSevenDays(), 4);
    }

    /** @test */
    public function can_see_the_total_number_of_hours_of_coaching_each_jockey_has_had_this_current_month_as_a_perecentage_of_4_hrs()
    {
        $faker = Factory::create();

        $jockey = factory(Jockey::class)->states('approved')->create();
        $coach1 = factory(Coach::class)->create();
        $coach2 = factory(Coach::class)->create();

        // dd($faker->dateTimeThisMonth());

        $activity1 = factory(Activity::class)->create([
          'coach_id' => $coach1->id,
          'duration' => '30',
          'start' => Carbon::now()->startOfMonth(),
          'end' => Carbon::now()->startOfMonth()->addMinutes(30),
        ]);

        $activity2 = factory(Activity::class)->create([
          'coach_id' => $coach2->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(),
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activity3 = factory(Activity::class)->create([
          'coach_id' => $coach2->id,
          'duration' => '30',
          'start' => Carbon::now()->startOfMonth(),
          'end' => Carbon::now()->startOfMonth()->addMinutes(30),
        ]);

        $activityNotInCurrentMonth = factory(Activity::class)->create([
          'coach_id' => $coach1->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth()->subMonth(2),
          'end' => Carbon::now()->startOfMonth()->subMonth(2)->addMinutes(60),
        ]);
        
        $activityInCurrentMonthButFuture = factory(Activity::class)->create([
          'coach_id' => $coach2->id,
          'duration' => '60',
          'start' => Carbon::now()->addMinutes(60),
          'end' => Carbon::now()->addMinutes(120),
        ]);

        $activityNotWithCoach = factory(Activity::class)->create([
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(),
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activityWithDifferentJockey = factory(Activity::class)->create([
          'coach_id' => $coach1->id,
          'duration' => '60',
          'start' => Carbon::now()->startOfMonth(),
          'end' => Carbon::now()->startOfMonth()->addMinutes(60),
        ]);

        $activity1->addJockey($jockey);
        $activity2->addJockey($jockey);
        $activity3->addJockey($jockey);
        $activityNotInCurrentMonth->addJockey($jockey);
        $activityInCurrentMonthButFuture->addJockey($jockey);

        $this->assertEquals($jockey->trainingTimeThisMonthPercentage(), 50);
    }

    /** @test */
    public function can_see_number_of_unread_notifications_they_have()
    {
        $coach = factory(Coach::class)->create();

        $notification1 = factory(Notification::class)->create([
        	'user_id' => $coach->id
        ]);
        $notification2 = factory(Notification::class)->create([
        	'user_id' => $coach->id
        ]);
        $notification3 = factory(Notification::class)->create([
        	'user_id' => $coach->id
        ]);
        $alreadyReadNotification = factory(Notification::class)->create([
        	'user_id' => $coach->id,
        	'read' => true
        ]);

        $notificationForOtherUser = factory(Notification::class)->create();

        $this->assertEquals($coach->numberOfUnreadNotifications(), 3);
    }

    /** @test */
    public function can_see_number_of_unread_messages() 
    {
        $coach = factory(Coach::class)->create();

        $message1 = factory(Message::class)->create();
        $message1->addRecipient($coach);
        $message2 = factory(Message::class)->create();
        $message2->addRecipient($coach);
        $message3 = factory(Message::class)->create();
        $message3->addRecipient($coach);

        $messageRead = factory(Message::class)->create();
        $messageRead->addRecipient($coach);
        $coach->markMessageAsRead($messageRead);


        $this->assertEquals($coach->messages->count(), 4);

        $this->assertEquals($coach->numberOfUnreadMessages(), 3);
    }
}