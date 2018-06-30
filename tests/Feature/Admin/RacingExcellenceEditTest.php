<?php

namespace Tests\Feature\Admin;

use App\Jobs\RacingExcellence\NotifyAllAmendedRacingExcellence;
use App\Jobs\RacingExcellence\NotifyCoachAddedToRacingExcellence;
use App\Jobs\RacingExcellence\NotifyCoachRemovalRacingExcellence;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\Notification;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use App\Models\RacingExcellenceParticipant;
use App\Models\RacingLocation;
use App\Models\SeriesType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RacingExcellenceEditTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
    public function can_edit_a_racing_excellence()
    {
    	// !!Participants are added and removed via Ajax on the page, not sent with the form!!
    	
    	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();
        $newCoach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $newCoach->id,
            'start_date' => '20/12/2020',
            'start_time' => '09:30',
            'location_id' => 2,
            'series_id' => 3,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response, $coach, $newCoach) {
        	$response->assertStatus(302);
        	$response->assertRedirect("/racing-excellence/{$racingExcellence->id}/results");
        	
        	$this->assertEquals($racingExcellence->coach_id, $newCoach->id);
        	$this->assertEquals($racingExcellence->start, Carbon::createFromFormat('d/m/Y H:i','20/12/2020 09:30'));
        	$this->assertEquals($racingExcellence->location_id, 2);
        	$this->assertEquals($racingExcellence->series_id, 3);


            // New coach notified of assignment
            Queue::assertPushed(NotifyCoachAddedToRacingExcellence::class, function($job) use ($response, $racingExcellence) {
                return $job->racingExcellence->id == $racingExcellence->id;
            });
        	// Old coach notified of removal
        	Queue::assertPushed(NotifyCoachRemovalRacingExcellence::class, function($job) use ($response, $racingExcellence, $coach) {
                return $job->racingExcellence->id == $racingExcellence->id &&
                	$job->oldCoachId == $coach->id;
            });
        	// Coach and jockeys notified of Race being amended.
        	Queue::assertPushed(NotifyAllAmendedRacingExcellence::class, function($job) use ($response, $racingExcellence) {
                return $job->racingExcellence->id == $racingExcellence->id;
            });

    	});    
    }

    /** @test */
    public function only_notify_of_coach_change_if_the_coach_has_changed()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '20/12/2020',
            'start_time' => '09:30',
            'location_id' => 2,
            'series_id' => 3,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response, $coach) {
        	$response->assertStatus(302);
        	$response->assertRedirect("/racing-excellence/{$racingExcellence->id}/results");
        	
        	$this->assertEquals($racingExcellence->coach_id, $coach->id);
     
            // New coach notified of assignment
            Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
        	// Old coach notified of removal
        	Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
        	// Coach and jockeys notified of Race being amended.
        	Queue::assertPushed(NotifyAllAmendedRacingExcellence::class, function($job) use ($response, $racingExcellence) {
                return $job->racingExcellence->id == $racingExcellence->id;
            });

    	});  
    }

    /** @test */
    public function coach_id_is_required()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => '',
            'start_date' => '20/12/2020',
            'start_time' => '09:30',
            'location_id' => 2,
            'series_id' => 3,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response, $coach) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('coach_id');
        	$this->assertEquals($racingExcellence->coach_id, $coach->id);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function coach_id__must_be_an_existing_coaches_id()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $jockeys1->first()->id,
            'start_date' => '20/12/2020',
            'start_time' => '09:30',
            'location_id' => 2,
            'series_id' => 3,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response, $coach) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('coach_id');
        	$this->assertEquals($racingExcellence->coach_id, $coach->id);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function location_id_is_required()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '20/12/2020',
            'start_time' => '09:30',
            'location_id' => '',
            'series_id' => 3,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('location_id');
        	$this->assertEquals($racingExcellence->location_id, 1);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function location_id_must_be_an_existing_RaceLocation_id()
    {
    	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '20/12/2020',
            'start_time' => '09:30',
            'location_id' => 9999,
            'series_id' => 3,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('location_id');
        	$this->assertEquals($racingExcellence->location_id, 1);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function series_id_is_required()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '20/12/2020',
            'start_time' => '09:30',
            'location_id' => 1,
            'series_id' => '',
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('series_id');
        	$this->assertEquals($racingExcellence->location_id, 1);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function series_id_must_be_an_existing_SeriesType_id()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '20/12/2020',
            'start_time' => '09:30',
            'location_id' => 1,
            'series_id' => 9999,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('series_id');
        	$this->assertEquals($racingExcellence->location_id, 1);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function start_date_is_required()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '',
            'start_time' => '09:30',
            'location_id' => 1,
            'series_id' => 1,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('start_date');
        	$this->assertEquals($racingExcellence->location_id, 1);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function start_date_must_be_in_the_correct_format()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '2018-05-30',
            'start_time' => '09:30',
            'location_id' => 1,
            'series_id' => 1,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('start_date');
        	$this->assertEquals($racingExcellence->location_id, 1);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function start_time_is_required()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '20/12/2020',
            'start_time' => '',
            'location_id' => 1,
            'series_id' => 1,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('start_time');
        	$this->assertEquals($racingExcellence->location_id, 1);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }

    /** @test */
    public function start_time_must_be_in_the_correct_format()
    {
      	Queue::fake();

        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockeys1 = factory(Jockey::class, 5)->create();
        $jockeys2 = factory(Jockey::class, 4)->create();


        $racingExcellence = factory(RacingExcellence::class)->create([
        	'coach_id' => $coach->id,
        	'location_id' => 1,
        	'series_id' => 2,
        	'start' => Carbon::parse('2018-11-06 1:00pm'),
        ]);

        $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);
        $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
        ]);

        $racingDivision1->addJockeysById($jockeys1->pluck('id'));
        $racingDivision2->addJockeysById($jockeys2->pluck('id'));


        $response = $this->actingAs($admin)->from("/admin/racing-excellence/{$racingExcellence->id}/edit")->put("/admin/racing-excellence/{$racingExcellence->id}", [
        	'coach_id' => $coach->id,
            'start_date' => '20/12/2020',
            'start_time' => '12:00:00',
            'location_id' => 1,
            'series_id' => 1,
        ]);

        tap($racingExcellence->fresh(), function($racingExcellence) use ($response) {
	        $response->assertStatus(302);
	        $response->assertRedirect('/admin/racing-excellence/' . $racingExcellence->id . '/edit');
        	$response->assertSessionHasErrors('start_time');
        	$this->assertEquals($racingExcellence->location_id, 1);
	        Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
	        Queue::assertNotPushed(NotifyCoachRemovalRacingExcellence::class);
	        Queue::assertNotPushed(NotifyAllAmendedRacingExcellence::class);
	    });
    }
}