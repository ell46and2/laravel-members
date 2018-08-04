<?php

namespace Tests\Feature\Coach;

use App\Jobs\Activity\NotifyJockeyAmendedActivity;
use App\Models\Activity;
use App\Models\ActivityLocation;
use App\Models\ActivityType;
use App\Models\Coach;
use App\Models\Invoice;
use App\Models\Jockey;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ActivityOneToOneEditTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function the_assigned_coach_can_edit_their_1to1_activity()
    {
        // !Coach cannot change the jockey when editing a 1to1.
        
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 2,
            'start_date' => '26/11/2028',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

  		tap($activity->fresh(), function($activity) use ($response, $jockey) {
  			$response->assertStatus(302);
     		$response->assertRedirect("/activity/{$activity->id}");
  			
  			$this->assertEquals($activity->activity_type_id, 2);
  			$this->assertEquals($activity->start, Carbon::createFromFormat('d/m/Y H:i','26/11/2028 13:00'));
  			$this->assertEquals($activity->duration, 30);
  			$this->assertEquals($activity->end, Carbon::createFromFormat('d/m/Y H:i','26/11/2028 13:30'));
  			$this->assertEquals($activity->location_id, 3);
  			$this->assertEquals($activity->location_name, '');

  			Queue::assertPushed(NotifyJockeyAmendedActivity::class, function($job) use ($activity) {
  				return $job->activity->id === $activity->id;
  			});
  		});     
    }

    /** @test */
    public function a_jockey_is_notified_when_start_or_location_is_changed_and_activity_is_in_the_future()
    {
        	
    }

    /** @test */
    public function a_jockey_is_NOT_notified_when_start_or_location_is_changed_and_activity_is_in_the_past()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
            'activity_type_id' => 1,
            'coach_id' => $coach->id,
            'start' => Carbon::now()->subDays(6),
            'duration' => null,
            'end' => null,
            'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->put("/coach/activity/{$activity->id}/single", [
            'activity_type_id' => 2,
            'start_date' => '26/11/2000',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response, $jockey) {
            $response->assertStatus(302);
            $response->assertRedirect("/activity/{$activity->id}");
            
            $this->assertEquals($activity->activity_type_id, 2);
            $this->assertEquals($activity->start, Carbon::createFromFormat('d/m/Y H:i','26/11/2000 13:00'));
            $this->assertEquals($activity->duration, 30);
            $this->assertEquals($activity->end, Carbon::createFromFormat('d/m/Y H:i','26/11/2000 13:30'));
            $this->assertEquals($activity->location_id, 3);
            $this->assertEquals($activity->location_name, '');

            Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
        });     
    }

    /** @test */
    public function a_jockey_is_NOT_notified_when_start_or_location_or_activity_type_are_not_changed()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
            'activity_type_id' => 1,
            'coach_id' => $coach->id,
            'start' => Carbon::createFromFormat('d/m/Y H:i','26/11/2020 13:00'),
            'duration' => null,
            'end' => null,
            'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->put("/coach/activity/{$activity->id}/single", [
            'activity_type_id' => 1,
            'start_date' => '26/11/2020',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 1,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response, $jockey) {
            $response->assertStatus(302);
            $response->assertRedirect("/activity/{$activity->id}");
            
            $this->assertEquals($activity->activity_type_id, 1);
            $this->assertEquals($activity->start, Carbon::createFromFormat('d/m/Y H:i','26/11/2020 13:00'));
            $this->assertEquals($activity->duration, 30);
            $this->assertEquals($activity->end, Carbon::createFromFormat('d/m/Y H:i','26/11/2020 13:30'));
            $this->assertEquals($activity->location_id, 1);
            $this->assertEquals($activity->location_name, '');

            Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
        }); 
    }

    /** @test */
    public function activity_type_id_is_required()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => '',
            'start_date' => '26/11/2018',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect("/coach/activity/{$activity->id}/edit");
        	$response->assertSessionHasErrors('activity_type_id');
        	$this->assertEquals($activity->activity_type_id, 1);
	        Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
	    });
    }

    /** @test */
    public function activity_type_id_must_exist_on_the_activity_types_table()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 9999,
            'start_date' => '26/11/2018',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect("/coach/activity/{$activity->id}/edit");
        	$response->assertSessionHasErrors('activity_type_id');
        	$this->assertEquals($activity->activity_type_id, 1);
	        Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
	    });	
    }

    /** @test */
    public function start_date_is_required()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 1,
            'start_date' => '',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect("/coach/activity/{$activity->id}/edit");
        	$response->assertSessionHasErrors('start_date');
        	$this->assertEquals($activity->activity_type_id, 1);
	        Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
	    });
    }

    /** @test */
    public function start_date_must_be_a_valid_date()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 1,
            'start_date' => 'not a date',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect("/coach/activity/{$activity->id}/edit");
        	$response->assertSessionHasErrors('start_date');
        	$this->assertEquals($activity->activity_type_id, 1);
	        Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
	    });
    }

    /** @test */
    public function start_time_is_required()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 1,
            'start_date' => '26/11/2018',
            'start_time' => '',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect("/coach/activity/{$activity->id}/edit");
        	$response->assertSessionHasErrors('start_time');
        	$this->assertEquals($activity->activity_type_id, 1);
	        Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
	    });
    }

    /** @test */
    public function start_time_must_be_a_valid_time()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 1,
            'start_date' => '26/11/2018',
            'start_time' => 'not a valid time',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect("/coach/activity/{$activity->id}/edit");
        	$response->assertSessionHasErrors('start_time');
        	$this->assertEquals($activity->activity_type_id, 1);
	        Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
	    });
    }

    /** @test */
    public function duration_is_optional()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 2,
            'start_date' => '26/11/2018',
            'start_time' => '13:00',
            'duration' => '',
            'location_id' => 3,
            'location_name' => ''
        ]);


        tap($activity->fresh(), function($activity) use ($response, $jockey) {
  			$response->assertStatus(302);
     		$response->assertRedirect("/activity/{$activity->id}");
  			
  			$this->assertEquals($activity->activity_type_id, 2);
  			$this->assertEquals($activity->start, Carbon::createFromFormat('d/m/Y H:i','26/11/2018 13:00'));
  			$this->assertEquals($activity->duration, null);
  			$this->assertEquals($activity->end, null);
  			$this->assertEquals($activity->location_id, 3);
  			$this->assertEquals($activity->location_name, '');

  			Queue::assertPushed(NotifyJockeyAmendedActivity::class, function($job) use ($activity) {
  				return $job->activity->id === $activity->id;
  			});
  		});     
    }

    /** @test */
    public function if_duration_was_previously_set_and_is_now_cleared_set_duration_and_end_to_null()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addMinutes(60),
        	'duration' => 30,
        	'end' => Carbon::now()->addMinutes(90),
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 2,
            'start_date' => '26/11/2018',
            'start_time' => '13:00',
            'duration' => '',
            'location_id' => 3,
            'location_name' => ''
        ]);


        tap($activity->fresh(), function($activity) use ($response, $jockey) {
  			$response->assertStatus(302);
     		$response->assertRedirect("/activity/{$activity->id}");
  			
  			$this->assertEquals($activity->activity_type_id, 2);
  			$this->assertEquals($activity->start, Carbon::createFromFormat('d/m/Y H:i','26/11/2018 13:00'));
  			$this->assertEquals($activity->duration, null);
  			$this->assertEquals($activity->end, null);
  			$this->assertEquals($activity->location_id, 3);
  			$this->assertEquals($activity->location_name, '');

  			Queue::assertPushed(NotifyJockeyAmendedActivity::class, function($job) use ($activity) {
  				return $job->activity->id === $activity->id;
  			});
  		});  	
    }

    /** @test */
    public function duration_must_be_a_whole_number()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
        	'activity_type_id' => 1,
        	'coach_id' => $coach->id,
        	'start' => Carbon::now()->addDays(6),
        	'duration' => null,
        	'end' => null,
        	'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
        	'activity_type_id' => 2,
            'start_date' => '26/11/2018',
            'start_time' => '13:00',
            'duration' => 30.25,
            'location_id' => 3,
            'location_name' => ''
        ]);

        tap($activity->fresh(), function($activity) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect("/coach/activity/{$activity->id}/edit");
        	$response->assertSessionHasErrors('duration');
        	$this->assertEquals($activity->duration, null);
	        Queue::assertNotPushed(NotifyJockeyAmendedActivity::class);
	    });     
    }

    /** @test */
    public function if_location_id_is_previously_set_and_now_location_name_is_set()
    {
    	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
            'activity_type_id' => 1,
            'coach_id' => $coach->id,
            'start' => Carbon::now()->addDays(6),
            'duration' => null,
            'end' => null,
            'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($coach)->put("/coach/activity/{$activity->id}/single", [
            'activity_type_id' => 2,
            'start_date' => '26/11/2028',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => '',
            'location_name' => 'Location name'
        ]);

        tap($activity->fresh(), function($activity) use ($response, $jockey) {
            $response->assertStatus(302);
            $response->assertRedirect("/activity/{$activity->id}");
            
            $this->assertEquals($activity->activity_type_id, 2);
            $this->assertEquals($activity->start, Carbon::createFromFormat('d/m/Y H:i','26/11/2028 13:00'));
            $this->assertEquals($activity->duration, 30);
            $this->assertEquals($activity->end, Carbon::createFromFormat('d/m/Y H:i','26/11/2028 13:30'));
            $this->assertEquals($activity->location_id, null);
            $this->assertEquals($activity->location_name, 'Location name');

            Queue::assertPushed(NotifyJockeyAmendedActivity::class, function($job) use ($activity) {
                return $job->activity->id === $activity->id;
            });	
        });
    }

    /** @test */
    public function a_coach_cannot_edit_another_coaches_activity()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $otherCoach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
            'activity_type_id' => 1,
            'coach_id' => $coach->id,
            'start' => Carbon::now()->addDays(6),
            'duration' => null,
            'end' => null,
            'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $response = $this->actingAs($otherCoach)->from("/coach/activity/{$activity->id}/edit")->put("/coach/activity/{$activity->id}/single", [
            'activity_type_id' => 2,
            'start_date' => '26/11/2018',
            'start_time' => '13:00',
            'duration' => 30,
            'location_id' => 3,
            'location_name' => ''
        ]);

       
        $response->assertStatus(403);          
    }

    /** @test */
    public function coach_cannot_edit_their_activity_if_its_been_added_to_an_invoice()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $activity = factory(Activity::class)->create([
            'activity_type_id' => 1,
            'coach_id' => $coach->id,
            'start' => Carbon::now()->addDays(6),
            'duration' => null,
            'end' => null,
            'location_id' => 1,
        ]);

        $activity->addJockey($jockey);

        $invoice = factory(Invoice::class)->create([
            'coach_id' => $coach->id,
            'status' => 'pending review'
        ]);

        $activity->invoiceLine()->create([
            'invoice_id' => $invoice->id,
        ]);

        $response = $this->actingAs($coach)->get("/coach/activity/{$activity->id}/edit");

        $response->assertStatus(403);
    }
}