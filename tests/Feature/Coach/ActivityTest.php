<?php

namespace Tests\Feature\Coach;

use App\Models\Activity;
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
            'jockeys' => [$jockey->id] // array of selected jockeys
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
        });
    }
}