<?php

namespace Tests\Feature\Admin;

use App\Mail\Jockey\Account\JockeyApprovedEmail;
use App\Models\Admin;
use App\Models\Jockey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class JockeyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_admin_can_approve_an_unapproved_jockey()
    {
    	Mail::fake();

        $admin = factory(Admin::class)->create();	
        $unapprovedJockey = factory(Jockey::class)->create();

        $this->assertEquals(0, $unapprovedJockey->approved);

        $response = $this->actingAs($admin)->post("/admin/jockeys/$unapprovedJockey->id/approve");

        // do we redirect back to /admin/jockeys/pending
        $response->assertRedirect("/jockey/{$unapprovedJockey->id}");
        
        $this->assertEquals(1, $unapprovedJockey->fresh()->approved);

        // setup job to email jockey saying they have been approved and can login
        // Have a button that links to login page.
        Mail::assertQueued(JockeyApprovedEmail::class, 1);
    	Mail::assertQueued(JockeyApprovedEmail::class, function($mail) use ($unapprovedJockey) {
    		return $mail->jockey->id == $unapprovedJockey->id &&
    		$mail->hasTo($unapprovedJockey->email);
    	});
    }

    /*
     setting the jockey's api_id will turn any external RacingExcellence participants and any CrmJockeys with that api_id into the jcp Jockey.
     */
}