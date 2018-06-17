<?php

namespace Tests\Feature\Coach;

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
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'activity_type_id' => 1,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'duration' => 30,
            'location_name' => 'Cheltenham racecourse',
        ], $overrides);
    }

    /** @test */
    public function a_coach_can_create_an_activity()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->post("/coach/activity", [
            'activity_type_id' => 1,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'duration' => 30,
            'location_name' => 'Cheltenham racecourse',
            'jockeys' => [$jockey->id] // array of selected jockeys from checkboxes
        ]);

        tap(Activity::first(), function($activity) use ($response, $coach, $jockey) {
        	$response->assertStatus(302);
            $response->assertRedirect("/coach/activity/{$activity->id}");

        	$this->assertTrue($activity->coach->is($coach));
        	$this->assertEquals($activity->jockeys->count(), 1);
        	$this->assertTrue($activity->jockeys->first()->is($jockey));

        	$this->assertEquals(Carbon::parse('06/11/2018 1:00pm'), $activity->start);
        	$this->assertEquals(30, $activity->duration);
        	$this->assertEquals(Carbon::parse('06/11/2018 1:00pm')->addMinutes(30), $activity->end);
        	$this->assertEquals('Cheltenham racecourse', $activity->location_name);
            // $this->assertEquals('Cheltenham racecourse', $activity->location);
            $this->assertNull($activity->location_id);

            $this->assertEquals(1, $activity->activity_type_id);
            $this->assertEquals(ActivityType::find(1)->name, $activity->type->name);

        	// Need to test 'new activity' notification is created for $jockey
        	// Probably add notification creation to a queue - as can be for many jockeys
        	tap(Notification::first(), function($notification) use ($activity, $coach, $jockey) {
        		// dd($activity);
        		$this->assertEquals($notification->notifiable_type, 'activity');
        		$this->assertEquals($notification->notifiable->id, $activity->id);
                $this->assertEquals($notification->linkUrl(), "activity/{$activity->id}");
        		$this->assertEquals($notification->user->id, $jockey->id);
        		// the body contains the $coach's fullname()
        		$this->assertRegexp("/{$coach->full_name}/", $notification->body);
        	});
        });
    }

    /** @test */
    public function activity_type_id_is_required()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/activity/create")->post("/coach/activity", $this->validParams([
            'activity_type_id' => '',
            'jockeys' => [$jockey->id]
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/activity/create');
        $response->assertSessionHasErrors('activity_type_id');
        $this->assertEquals(0, Activity::count());
        $this->assertEquals(0, Notification::count());  
    }

    /** @test */
    public function activity_type_id_must_exist_on_the_activity_types_table()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/activity/create")->post("/coach/activity", $this->validParams([
            'activity_type_id' => 9999,
            'jockeys' => [$jockey->id]
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/activity/create');
        $response->assertSessionHasErrors('activity_type_id');
        $this->assertEquals(0, Activity::count());
        $this->assertEquals(0, Notification::count());  
    }

    /** @test */
    public function start_date_is_required()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/activity/create")->post("/coach/activity", $this->validParams([
            'start_date' => '',
            'jockeys' => [$jockey->id]
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/activity/create');
        $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, Activity::count());
        $this->assertEquals(0, Notification::count());
    }

    /** @test */
    public function start_date_must_be_a_valid_date()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/activity/create")->post("/coach/activity", $this->validParams([
            'start_date' => 'not a date',
            'jockeys' => [$jockey->id]
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/activity/create');
        $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, Activity::count());
        $this->assertEquals(0, Notification::count());  
    }

    /** @test */
    public function start_time_is_required()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/activity/create")->post("/coach/activity", $this->validParams([
            'start_time' => '',
            'jockeys' => [$jockey->id]
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/activity/create');
        $response->assertSessionHasErrors('start_time');
        $this->assertEquals(0, Activity::count());
        $this->assertEquals(0, Notification::count());
    }

    /** @test */
    public function start_time_must_be_a_valid_time()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/activity/create")->post("/coach/activity", $this->validParams([
            'start_time' => 'not a valid time',
            'jockeys' => [$jockey->id]
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/activity/create');
        $response->assertSessionHasErrors('start_time');
        $this->assertEquals(0, Activity::count());
        $this->assertEquals(0, Notification::count());
    }  

    /** @test */
    public function duration_is_optional()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/activity/create")->post("/coach/activity", $this->validParams([
            'duration' => '',
            'jockeys' => [$jockey->id]
        ]));

        tap(Activity::first(), function($activity) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect("/coach/activity/{$activity->id}");
            $this->assertEquals(null, $activity->duration);
            $this->assertEquals(null, $activity->end);
        });
    }

    /** @test */
    public function duration_must_be_a_whole_number()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->from("/coach/activity/create")->post("/coach/activity", $this->validParams([
            'duration' => 30.25,
            'jockeys' => [$jockey->id]
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/coach/activity/create');
        $response->assertSessionHasErrors('duration');
        $this->assertEquals(0, Activity::count());
        $this->assertEquals(0, Notification::count());
    }

    /** @test */
    public function cannot_add_a_jockey_they_are_not_assigned_to_to_an_activity()
    {
        	
    }

    /** @test */
    public function an_activity_can_either_have_a_location_id_or_a_location_name()
    {
        // location can come from a drop down of locations OR can be free text.  
        // It can't have both sent in the form request - look at validating this.
        // Also on editing if free text chnges to location_id or vice versa will need to null
        // the other value. - possibly an observer could handle this for us.
        
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->post("/coach/activity", [
            'activity_type_id' => 1,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'duration' => 30,
            'location_name' => '',
            'location_id' => 1,
            'jockeys' => [$jockey->id] // array of selected jockeys from checkboxes
        ]);

        tap(Activity::first(), function($activity) use ($response, $coach, $jockey) {
            $this->assertNull($activity->location_name);
            $this->assertEquals(1, $activity->location_id);
            $this->assertEquals(ActivityLocation::find(1)->name, $activity->location->name);
            $this->assertEquals(ucfirst(ActivityLocation::find(1)->name), $activity->formattedLocation);
        });
    }

    /** @test */
    public function a_coach_can_add_jockey_feedback()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->post("/coach/activity", [
            'activity_type_id' => 1,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'duration' => 30,
            'location' => 'Cheltenham racecourse',
            'jockeys' => [$jockey->id], // array of selected jockeys from checkboxes
            "feedback" => [
                [$jockey->id => 'Jockeys feedback from coach']
            ], // array of feedback with the key being each jockeys id.
        ]);

        tap(Activity::first(), function($activity) use ($response, $coach, $jockey) {
            $response->assertStatus(302);
            $response->assertRedirect("/coach/activity/{$activity->id}");

            $this->assertTrue($activity->jockeys->first()->is($jockey));

            $this->assertEquals($activity->jockeys->first()->pivot->feedback, 'Jockeys feedback from coach');
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
        $coach = factory(Coach::class)->create();
        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->post("/coach/activity", [
            'activity_type_id' => 1,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'duration' => 30,
            'location_name' => 'Cheltenham racecourse',
            'jockeys' => [$jockey1->id, $jockey2->id, $jockey3->id], // array of selected jockeys from checkboxes
            "feedback" => [
                [$jockey1->id => 'Jockey1 feedback from coach'],
                [$jockey2->id => ''],
                [$jockey3->id => 'Jockey3 feedback from coach']
            ], // array of feedback with the key being each jockeys id.
        ]);

        tap(Activity::first(), function($activity) use ($response, $coach, $jockey1, $jockey2, $jockey3) {
            $response->assertStatus(302);
            $response->assertRedirect("/coach/activity/{$activity->id}");

            $this->assertTrue($activity->coach->is($coach));
            $this->assertEquals($activity->jockeys->count(), 3);
            $activity->jockeys->assertContains($jockey1);
            $activity->jockeys->assertContains($jockey2);
            $activity->jockeys->assertContains($jockey3);

            $this->assertEquals($activity->jockeys->first()->pivot->feedback, 'Jockey1 feedback from coach');
            $this->assertEquals($activity->jockeys->find($jockey2->id)->pivot->feedback, '');
            $this->assertEquals($activity->jockeys->find($jockey3->id)->pivot->feedback, 'Jockey3 feedback from coach');

            $this->assertEquals(Carbon::parse('06/11/2018 1:00pm'), $activity->start);
            $this->assertEquals(30, $activity->duration);
            $this->assertEquals(Carbon::parse('06/11/2018 1:00pm')->addMinutes(30), $activity->end);
            $this->assertEquals('Cheltenham racecourse', $activity->location_name);

            // Need to test 'new activity' notification is created for $jockey
            // Probably add notification creation to a queue - as can be for many jockeys
            tap(Notification::first(), function($notification) use ($activity, $coach, $jockey1) {
                // dd($activity);
                $this->assertEquals($notification->notifiable_type, 'activity');
                $this->assertEquals($notification->notifiable->id, $activity->id);
                $this->assertEquals($notification->linkUrl(), "activity/{$activity->id}");
                $this->assertEquals($notification->user->id, $jockey1->id);
                // the body contains the $coach's fullname()
                $this->assertRegexp("/{$coach->full_name}/", $notification->body);
            });

            tap(Notification::find(2), function($notification) use ($activity, $coach, $jockey2) {
                // dd($activity);
                $this->assertEquals($notification->notifiable_type, 'activity');
                $this->assertEquals($notification->notifiable->id, $activity->id);
                $this->assertEquals($notification->linkUrl(), "activity/{$activity->id}");
                $this->assertEquals($notification->user->id, $jockey2->id);
                // the body contains the $coach's fullname()
                $this->assertRegexp("/{$coach->full_name}/", $notification->body);
            });

            tap(Notification::find(3), function($notification) use ($activity, $coach, $jockey3) {
                // dd($activity);
                $this->assertEquals($notification->notifiable_type, 'activity');
                $this->assertEquals($notification->notifiable->id, $activity->id);
                $this->assertEquals($notification->linkUrl(), "activity/{$activity->id}");
                $this->assertEquals($notification->user->id, $jockey3->id);
                // the body contains the $coach's fullname()
                $this->assertRegexp("/{$coach->full_name}/", $notification->body);
            });
        });
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

    /** @test */
    public function can_add_a_comment_for_an_individaul_jockey_to_an_activity_they_are_coach_for()
    {
            
    }

    /** @test */
    public function cannot_add_a_comment_for_an_activity_they_are_not_coach_for()
    {
            
    }

    /** @test */
    public function can_add_a_comment_for_all_jockeys_that_belong_to_an_activity()
    {
            
    }

    /** @test */
    public function can_view_all_comments_for_an_activity_including_other_coaches()
    {
            
    }
}