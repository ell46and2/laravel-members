<?php

namespace Tests\Feature\Coach;

use App\Models\Activity;
use App\Models\Coach;
use App\Models\Jockey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ActivityJockeyFeedbackTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_coach_can_add_individual_jockey_feedback()
    {
     	$coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	$activity->addJockey($jockey);

    	$response = $this->actingAs($coach)->post("/activity/{$activity->id}/feedback/{$jockey->id}", [
            'feedback' => 'the jockeys feedback',
        ]);

        tap($activity->fresh(), function($activity) use ($response, $coach, $jockey) {
            // $response->assertStatus(302);
            // $response->assertRedirect("/coach/activity/{$activity->id}");
             
            $this->assertEquals($activity->jockeys->first()->pivot->feedback, 'the jockeys feedback');
        });   
    }
}
