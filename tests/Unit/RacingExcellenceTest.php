<?php

namespace Tests\Unit;

use App\Jobs\RacingExcellence\NotifyAddedToRacingExcellence;
use App\Jobs\RacingExcellence\NotifyRemovedFromRacingExcellence;
use App\Models\Admin;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use App\Models\RacingExcellenceParticipant;
use App\Models\SeriesType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RacingExcellenceTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_racing_excellence_is_made_up_of_one_or_more_divisions()
	{
	    $racingExcellence = factory(RacingExcellence::class)->create();
	    $division1 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);
	    $division2 = factory(RacingExcellenceDivision::class)->create([
        	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $divisions = $racingExcellence->divisions;

	    $this->assertEquals($divisions->count(), 2);
	    $divisions->assertContains($division1);
	    $divisions->assertContains($division2);
	}

}