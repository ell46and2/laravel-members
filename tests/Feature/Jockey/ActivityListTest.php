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
    	// create upcoming activities
    	// create upcoming racing excellence
    	// create past activities
    	// create past racing excellence
    	
    	// test that activities are combined with racing excellence and in the correct order
        
        $jockey = factory(Jockey::class)->states('approved')->create();

        $upcomingActivity1 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(2),
            'duration' => '30',
            'end' => Carbon::now()->addDays(2),
        ]);
        $upcomingActivity1->addJockey($jockey);

        $pastActivity = factory(Activity::class)->create([
            'start' => Carbon::now()->subDays(1),
            'duration' => '30',
            'end' => Carbon::now()->subDays(1),
        ]);

        $pastActivity->addJockey($jockey);

        $upcomingActivity2 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(4),
            'duration' => '30',
            'end' => Carbon::now()->addDays(4),
        ]);
        $upcomingActivity2->addJockey($jockey);

        $upcomingActivity3 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(5),
            'duration' => '30',
            'end' => Carbon::now()->addDays(5),
        ]);
        $upcomingActivity3->addJockey($jockey);

        $upcomingActivity4 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(7),
            'duration' => '30',
            'end' => Carbon::now()->addDays(7),
        ]);
        $upcomingActivity4->addJockey($jockey);

        $upcomingActivity5 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(8),
            'duration' => '30',
            'end' => Carbon::now()->addDays(8),
        ]);
        $upcomingActivity5->addJockey($jockey);

        $upcomingActivity6 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(9),
            'duration' => '30',
            'end' => Carbon::now()->addDays(9),
        ]);
        $upcomingActivity6->addJockey($jockey);

        $upcomingActivity7 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(10),
            'duration' => '30',
            'end' => Carbon::now()->addDays(10),
        ]);
        $upcomingActivity7->addJockey($jockey);

        $upcomingActivity8 = factory(Activity::class)->create([
            'start' => Carbon::now()->addDays(11),
            'duration' => '30',
            'end' => Carbon::now()->addDays(11),
        ]);
        $upcomingActivity8->addJockey($jockey);

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

        $pastRacingExcellence = factory(RacingExcellence::class)->create([
            'start' => Carbon::now()->subDays(1)
        ]);
        $pastRacingExcellenceDivision = factory(RacingExcellenceDivision::class)->create([
            'racing_excellence_id' => $pastRacingExcellence->id
        ]);

        $pastRacingExcellenceDivision->addJockeysById(collect($jockey->id));

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

        $upcomingRacingExcellenceC = factory(RacingExcellence::class)->create([
            'start' => Carbon::now()->addDays(6)
        ]);
        $upcomingRacingExcellenceCDivision1 = factory(RacingExcellenceDivision::class)->create([
            'racing_excellence_id' => $upcomingRacingExcellenceC->id
        ]);

        $upcomingRacingExcellenceCDivision1->addJockeysById(collect($jockey->id));

        $events = $jockey->upcomingEvents()->take(10);

        // check order is most recent ascending
        $this->assertTrue($events->values()[0]->is($upcomingRacingExcellence));
        $this->assertTrue($events->values()[1]->is($upcomingActivity1));
        $this->assertTrue($events->values()[2]->is($upcomingRacingExcellenceB));
        $this->assertTrue($events->values()[3]->is($upcomingActivity2));
        $this->assertTrue($events->values()[4]->is($upcomingActivity3));
        $this->assertTrue($events->values()[5]->is($upcomingRacingExcellenceC));
        $this->assertTrue($events->values()[6]->is($upcomingActivity4));
        $this->assertTrue($events->values()[7]->is($upcomingActivity5));
        $this->assertTrue($events->values()[8]->is($upcomingActivity6));
        $this->assertTrue($events->values()[9]->is($upcomingActivity7));

        $this->assertEquals($events->count(), 10);
    }
}