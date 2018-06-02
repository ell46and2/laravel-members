<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RacingExcellenceTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function can_create_a_racing_excellence()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 10)->create();

        $jockeys2 = factory(Jockey::class, 8)->create();

        $response = $this->actingAs($admin)->post("/admin/racing-excellence/", [
        	'coach_id' => $coach->id,
            'start' => Carbon::parse('2018-11-06 1:00pm'), // NEED TO CHANGE
            'location' => 'Cheltenham racecourse',
            "divisions" => [
            	['jockeys' => $jockeys1->pluck('id')->toArray(), 'external_participants' => ['John Doe', 'Jane Doe']],
            	['jockeys' => $jockeys2->pluck('id')->toArray(), 'external_participants' => []]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        tap(RacingExcellence::first(), function($racingExcellence) use ($response, $jockeys1, $jockeys2, $coach) {
        	// $response->assertStatus(302);
         //    $response->assertRedirect("/coach/activity/{$activity->id}");

        	// dd($coach);
        	$this->assertTrue($racingExcellence->coach->is($coach));
        	$this->assertEquals(Carbon::parse('2018-11-06 1:00pm'), $racingExcellence->start);
        	$this->assertEquals('Cheltenham racecourse', $racingExcellence->location);

        	$this->assertEquals($racingExcellence->participants->count(), 20);
        	$this->assertEquals($racingExcellence->jockeys->count(), 18);

            // dd($coach->notifications->first());

        	// notifications sent to coach and jockeys.
            // tap($coach->notification->first(), function($notification) use ($activity, $coach, $jockey) {
            //     // dd($activity);
            //     $this->assertEquals($notification->notifiable_type, 'activity');
            //     $this->assertEquals($notification->notifiable->id, $activity->id);
            //     $this->assertEquals($notification->linkUrl(), "activity/{$activity->id}");
            //     $this->assertEquals($notification->user->id, $jockey->id);
            //     // the body contains the $coach's fullname()
            //     $this->assertRegexp("/{$coach->fullName()}/", $notification->body);
            // }); 
        });
    }
}