<?php

namespace Tests\Feature\Jockey;


use App\Models\Activity;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ActivityListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_see_a_paginated_list_of_their_upcoming_activities_and_racing_excellence()
    {
    	// Carbon::now()->addMinutes(120)
        
        $jockey = factory(Jockey::class)->states('approved')->create();

        $upcomingActivity1 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(2),
            'duration' => '30',
            'end' => Carbon::now()->addDays(2),
        ]);
        $upcomingActivity1->addJockey($jockey);

        $upcomingRacingExcellence = factory(RacingExcellence::class)->create([
            'start' => Carbon::now()->addDays(1)
        ]);
        $upcomingRacingExcellenceDivision1 = factory(RacingExcellenceDivision::class)->create([
            'racing_excellence_id' => $upcomingRacingExcellence->id
        ]);
        $upcomingRacingExcellenceDivision2 = factory(RacingExcellenceDivision::class)->create([
            'racing_excellence_id' => $upcomingRacingExcellence->id
        ]);
        $upcomingRacingExcellenceDivision1->addJockeysById(collect($jockey->id));

        $upcomingRacingExcellenceB = factory(RacingExcellence::class)->create([
            'start' => Carbon::now()->addDays(3)
        ]);
        $upcomingRacingExcellenceBDivision1 = factory(RacingExcellenceDivision::class)->create([
            'racing_excellence_id' => $upcomingRacingExcellenceB->id
        ]);
        $upcomingRacingExcellenceBDivision2 = factory(RacingExcellenceDivision::class)->create([
            'racing_excellence_id' => $upcomingRacingExcellenceB->id
        ]);
        $upcomingRacingExcellenceBDivision2->addJockeysById(collect($jockey->id));

        $events = $jockey->upcomingEvents()->take(10);

        dd($events);
    	
    	// create upcoming activities
    	// create upcoming racing excellence
    	// create past activities
    	// create past racing excellence
    	
    	// test that activities are combined with racing excellence and in the correct order
    	
    	//test that only upcoming are displayed.
    	
    	// test that list is only activities and racing excellence for that jockey.
    }
}