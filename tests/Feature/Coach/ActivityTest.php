<?php

namespace Tests\Feature\Coach;

use App\Models\Activity;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_coach_can_create_an_activity()
    {
        $coach = factory(User::class)->states('coach')->create();
        $jockey = factory(User::class)->create();

        $response = $this->actingAs($coach)->post("/coach/activity", [
            'start' => Carbon::parse('2018-11-06 1:00pm'),
            'duration' => 30,
            'location' => 'Cheltenham racecourse',
            'jockeys' => [$jockey->id] // array of selected jockeys from checkboxes
        ]);

        tap(Activity::first(), function($activity) use ($response, $coach, $jockey) {
        	$response->assertStatus(302);
            $response->assertRedirect("/coach/activity/{$activity->id}");

        	$this->assertTrue($activity->coach->is($coach));
        	$this->assertEquals($activity->jockeys->count(), 1);
        	$this->assertTrue($activity->jockeys->first()->is($jockey));

        	$this->assertEquals(Carbon::parse('2018-11-06 1:00pm'), $activity->start);
        	$this->assertEquals(30, $activity->duration);
        	$this->assertEquals(Carbon::parse('2018-11-06 1:00pm')->addMinutes(30), $activity->end);
        	$this->assertEquals('Cheltenham racecourse', $activity->location);

        	// Need to test 'new activity' notification is created for $jockey
        	// Probably add notification creation to a queue - as can be for many jockeys
        	tap(Notification::first(), function($notification) use ($activity, $coach, $jockey) {
        		// dd($activity);
        		$this->assertEquals($notification->notifiable_type, 'App\Models\Activity');
        		$this->assertEquals($notification->notifiable->id, $activity->id);
        		$this->assertEquals($notification->user->id, $jockey->id);
        		// the body contains the $coach's fullname()
        		$this->assertRegexp("/{$coach->fullName()}/", $notification->body);
        	});
        });
    }

    /** @test */
    public function a_coach_can_edit_their_activity()
    {
        	
    }

    /** @test */
    public function a_coach_can_delete_their_activity()
    {
        	
    }

    /** @test */
    public function a_coach_cannot_edit_another_coaches_activity()
    {
        	
    }

    /** @test */
    public function a_coach_cannot_delete_another_coaches_activity()
    {
        	
    }

    /** @test */
    public function a_coach_can_create_a_group_activity()
    {
        	
    }

    /** @test */
    public function a_coach_can_only_add_jockeys_from_their_group_to_the_group_activity()
    {
        	
    }

    /** @test */
    public function a_coach_can_add_mileage_to_an_activity()
    {
        	
    }

    /** @test */
    public function an_away_day_activity_doesnt_have_a_jockey()
    {
        	
    }

    /** @test */
    public function an_activity_must_have_a_jockey_assigned_unless_its_an_away_day() // requires more thought
    {
        	
    }


}