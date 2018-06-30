<?php

namespace Tests\Feature\Admin;

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
use Tests\TestCase;

class RacingExcellenceTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function can_create_a_racing_excellence()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'location_id' => 1,
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        tap(RacingExcellence::first(), function($racingExcellence) use ($response, $jockey1, $jockey2, $coach) {
            $response->assertStatus(302);
            $response->assertRedirect("/racing-excellence/{$racingExcellence->id}/results");

            $this->assertTrue($racingExcellence->coach->is($coach));
            $this->assertEquals(Carbon::createFromFormat('d/m/Y H:i','06/11/2018 13:00'), $racingExcellence->start);
            $this->assertEquals(1, $racingExcellence->location_id);
            $this->assertEquals(RacingLocation::find(1)->name, $racingExcellence->location->name);
            $this->assertEquals(1, $racingExcellence->series_id);
            $this->assertEquals(SeriesType::find(1)->name, $racingExcellence->series->name);

            // dd($racingExcellence->participants);

            $this->assertEquals($racingExcellence->participants->count(), 7);
            $this->assertEquals($racingExcellence->jockeyParticipants->count(), 5);

            // Total number of notifications sent - 5 Jockeys + 1 Coach
            $this->assertEquals(Notification::all()->count(), 6);
            
            // Coach Notification
            $this->assertEquals($coach->notifications->count(), 1);
            $this->assertEquals($coach->notifications->first()->notifiable_type, 'racing-excellence');
            $this->assertEquals($coach->notifications->first()->notifiable->id, $racingExcellence->id);

            // Jockeys Notifications - test just first of each division has received a notification
            $this->assertEquals($jockey1->notifications->count(), 1);
            $this->assertEquals($jockey1->notifications->first()->notifiable_type, 'racing-excellence');
            $this->assertEquals($jockey1->notifications->first()->notifiable->id, $racingExcellence->id);

            $this->assertEquals($jockey2->notifications->count(), 1);
            $this->assertEquals($jockey2->notifications->first()->notifiable_type, 'racing-excellence');
            $this->assertEquals($jockey2->notifications->first()->notifiable->id, $racingExcellence->id);
        });
    }

    /** @test */
    public function coach_id_is_required()
    {
        $admin = factory(Admin::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => '',
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'location_id' => 1,
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('coach_id');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());  
    }

    /** @test */
    public function coach_id_must_be_an_existing_coaches_id()
    {
        $admin = factory(Admin::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $jockey1->id,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'location_id' => 1,
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('coach_id');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());  
    }

    /** @test */
    public function location_id_is_required()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'location_id' => '',
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('location_id');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }

    /** @test */
    public function location_id_must_be_an_existing_RaceLocation_id()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'location_id' => 9999,
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('location_id');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }

     /** @test */
    public function series_id_is_required()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'location_id' => 1,
            'series_id' => '',
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('series_id');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }

    /** @test */
    public function series_id_must_be_an_existing_SeriesType_id()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'location_id' => 1,
            'series_id' => 9999,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('series_id');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }

    /** @test */
    public function start_date_is_required()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '',
            'start_time' => '13:00',
            'location_id' => 1,
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }

    /** @test */
    public function start_date_must_be_in_the_correct_format()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '2018-05-30',
            'start_time' => '13:00',
            'location_id' => 1,
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }

    /** @test */
    public function start_time_is_required()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '06/11/2018',
            'start_time' => '',
            'location_id' => 1,
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('start_time');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }

    /** @test */
    public function start_time_must_be_in_the_correct_format()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '06/11/2018',
            'start_time' => '12:00:00',
            'location_id' => 1,
            'series_id' => 1,
            "divisions" => [
                1 => [
                    'jockeys' => [$jockey1->id => "on", $jockey2->id => "on", $jockey3->id => "on"], 
                    'external_participants' => ['John Doe' => "on", 'Jane Doe' => "on"]
                ],
                2 => [
                    'jockeys' => [$jockey4->id => "on", $jockey5->id => "on"], 
                    'external_participants' => []
                ]
            ], // array of selected jockeys from checkboxes and array of external jockey names
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('start_time');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }

    /** @test */
    public function divisions_is_required()
    {
        $admin = factory(Admin::class)->create();
        $coach = factory(Coach::class)->create();

        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $jockey3 = factory(Jockey::class)->create();

        $jockey4 = factory(Jockey::class)->create();
        $jockey5 = factory(Jockey::class)->create();

        $response = $this->actingAs($admin)->from("/admin/racing-excellence/create")->post("/admin/racing-excellence/", [
            'coach_id' => $coach->id,
            'start_date' => '06/11/2018',
            'start_time' => '13:00',
            'location_id' => 1,
            'series_id' => 1,
            "divisions" => [],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/racing-excellence/create');
        $response->assertSessionHasErrors('divisions');
        $this->assertEquals(0, RacingExcellence::count());
        $this->assertEquals(0, RacingExcellenceDivision::count());
        $this->assertEquals(0, RacingExcellenceParticipant::count());
        $this->assertEquals(0, Notification::count());    
    }
}