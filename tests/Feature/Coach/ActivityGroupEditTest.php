<?php

namespace Tests\Feature\Coach;

use App\Jobs\Activity\NotifyJockeyAmendedActivity;
use App\Models\Activity;
use App\Models\ActivityLocation;
use App\Models\ActivityType;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ActivityGroupEditTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_assigned_coach_can_edit_their_group_activity()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();
        $jockey4 = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey1);
        $activity->addJockey($jockey2);
        $activity->addJockey($jockey3);

        $response = $this->actingAs($coach)->put("/coach/activity/{$activity->id}/group", [
        	'activity_type_id' => 2,
            'start_date' => '26/11/2028',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => '',
            'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey4->id => "on"],
        ]);

        tap($activity->fresh(), function($activity) use ($response, $jockey1, $jockey2, $jockey3, $jockey4) {
        	$response->assertStatus(302);
     		$response->assertRedirect("/activity/{$activity->id}");

     		$this->assertEquals($activity->activity_type_id, 2);
  			$this->assertEquals($activity->start, Carbon::createFromFormat('d/m/Y H:i','26/11/2028 13:00'));
  			$this->assertEquals($activity->duration, 30);
  			$this->assertEquals($activity->end, Carbon::createFromFormat('d/m/Y H:i','26/11/2028 13:30'));
  			$this->assertEquals($activity->location_id, 3);
  			$this->assertEquals($activity->location_name, '');

  			$activity->jockeys->assertContains($jockey1);
            $activity->jockeys->assertContains($jockey2);
            $activity->jockeys->assertContains($jockey4);
            $activity->jockeys->assertNotContains($jockey3);
        });
    }
}