<?php

namespace Tests\Feature\Admin;

use App\Jobs\RacingExcellence\NotifyCoachAddedToRacingExcellence;
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

class RacingExcellenceTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function can_assign_a_coach_to_a_racing_excellence()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $coach = factory(Coach::class)->create([]);

        $race = factory(RacingExcellence::class)->create([
            'coach_id' => null
        ]);

        $response = $this->actingAs($admin)->post("/racing-excellence/{$race->id}/assign-coach", [
            'coach_id' => $coach->id
        ]);

        tap($race->fresh(), function($race) use ($response, $coach) {
            $response->assertStatus(302);

            $this->assertEquals($race->coach_id, $coach->id);

            Queue::assertPushed(NotifyCoachAddedToRacingExcellence::class, function($job) use ($response, $race) {
                return $job->racingExcellence->id == $race->id;
            });
        }); 
    }

    /** @test */
    public function can_only_assign_coaches_to_the_race()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $race = factory(RacingExcellence::class)->create([
            'coach_id' => null
        ]);

        $response = $this->actingAs($admin)->post("/racing-excellence/{$race->id}/assign-coach", [
            'coach_id' => $admin->id
        ]);

        tap($race->fresh(), function($race) use ($response) {
            $response->assertStatus(302);

            $this->assertEquals($race->coach_id, null);

            Queue::assertNotPushed(NotifyCoachAddedToRacingExcellence::class);
        }); 
    }
}