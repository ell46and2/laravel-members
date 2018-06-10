<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\Notification;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RacingExcellenceTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function can_create_a_racing_excellence() // Need to add Validation
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
        	$response->assertStatus(302);
         	$response->assertRedirect("/admin/racing-excellence/{$racingExcellence->id}");

        	$this->assertTrue($racingExcellence->coach->is($coach));
        	$this->assertEquals(Carbon::parse('2018-11-06 1:00pm'), $racingExcellence->start);
        	$this->assertEquals('Cheltenham racecourse', $racingExcellence->location);

        	$this->assertEquals($racingExcellence->participants->count(), 20);
        	$this->assertEquals($racingExcellence->jockeys->count(), 18);

            // Total number of notifications sent - 18 Jockeys + 1 Coach
            $this->assertEquals(Notification::all()->count(), 19);
            
            // Coach Notification
            $this->assertEquals($coach->notifications->count(), 1);
            $this->assertEquals($coach->notifications->first()->notifiable_type, 'racing-excellence');
        	$this->assertEquals($coach->notifications->first()->notifiable->id, $racingExcellence->id);

        	// Jockeys Notifications - test just first of each division has received a notification
            $this->assertEquals($jockeys1->first()->notifications->count(), 1);
            $this->assertEquals($jockeys1->first()->notifications->first()->notifiable_type, 'racing-excellence');
        	$this->assertEquals($jockeys1->first()->notifications->first()->notifiable->id, $racingExcellence->id);

        	$this->assertEquals($jockeys2->first()->notifications->count(), 1);
            $this->assertEquals($jockeys2->first()->notifications->first()->notifiable_type, 'racing-excellence');
        	$this->assertEquals($jockeys2->first()->notifications->first()->notifiable->id, $racingExcellence->id);
        });
    }

    /** @test */
    public function can_edit_a_racing_excellence() // Needs further looking at.
    {
        // $admin = factory(Admin::class)->create();
        // $coach = factory(Coach::class)->create();
        // $newCoach = factory(Coach::class)->create();

        // $jockeys1ToStay = factory(Jockey::class, 5)->create();
        // $jockeys1ToRemove = factory(Jockey::class, 5)->create();
        // $jockeys1ToAdd = factory(Jockey::class, 2)->create();

        // $jockeys2ToStay = factory(Jockey::class, 4)->create();
        // $jockeys2ToRemove = factory(Jockey::class, 4)->create();
        // $jockeys2ToAdd = factory(Jockey::class, 5)->create();

        // $racingExcellence = factory(RacingExcellence::class)->create([
        // 	'coach_id' => $coach->id,
        // 	'location' => 'Cheltenham racecourse',
        // 	'start' => Carbon::parse('2018-11-06 1:00pm'),
        // ]);

        // $racingDivision1 = factory(RacingExcellenceDivision::class)->create([
        // 	'racing_excellence_id' => $racingExcellence->id
        // ]);
        // $racingDivision2 = factory(RacingExcellenceDivision::class)->create([
        // 	'racing_excellence_id' => $racingExcellence->id
        // ]);

        // $racingDivision1->addJockeysById($jockeys1ToStay->pluck('id'));
        // $racingDivision1->addJockeysById($jockeys1ToRemove->pluck('id'));
        // $racingDivision1->addExternalParticipants(collect(['John Doe', 'Jane Doe']));
        // $racingDivision2->addJockeysById($jockeys2ToStay->pluck('id'));
        // $racingDivision2->addJockeysById($jockeys2ToRemove->pluck('id'));

        // $this->assertEquals($racingExcellence->participants->count(), 20);

        // $response = $this->actingAs($admin)->put("/admin/racing-excellence/{$racingExcellence->id}", [
        // 	'coach_id' => $newCoach->id,
        //     'start' => Carbon::parse('2018-12-04 11:00am'),
        //     'location' => 'New Location',
        //     "divisions" => [
        //     	['jockeys' => $jockeys1->pluck('id')->toArray(), 'external_participants' => ['John Doe', 'Jane Doe']],
        //     	['jockeys' => $jockeys2->pluck('id')->toArray(), 'external_participants' => []]
        //     ], // array of selected jockeys from checkboxes and array of external jockey names
        // ]);

        // New coach notified of assignment
        // Old coach notified of removal
        // New jockeys notified of being added to race
        // Old jockeys notified of being removed from race
        // Coach and jockeys notified of Race being amended.
    }
}