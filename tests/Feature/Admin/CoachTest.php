<?php

namespace Tests\Feature\Admin;

use App\Mail\Coach\Account\CoachCreatedEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CoachTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_admin_can_create_a_coach_user()
    {
    	Mail::fake();

     	$admin = factory(User::class)->states('admin')->create();

     	$response = $this->actingAs($admin)->post("/admin/coaches", [
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        	'telephone' => '01242 222333',	
        	'street_address' => '123 street',
        	'city' => 'Cheltenham',
        	'postcode' => 'GL50 1ST',
        	'email' => 'jane@example.com',
        ]);

        tap(User::getAllCoaches()->first(), function($coach) use ($response) {
        	$response->assertStatus(302);
        	$response->assertRedirect("/admin/coaches/{$coach->id}");

        	$this->assertEquals('Jane', $coach->first_name);
        	$this->assertEquals('Doe', $coach->last_name);
        	$this->assertEquals('01242 222333', $coach->telephone);
        	$this->assertEquals('123 street', $coach->street_address);
        	$this->assertEquals('Cheltenham', $coach->city);
        	$this->assertEquals('GL50 1ST', $coach->postcode);
        	$this->assertEquals('jane@example.com', $coach->email);

        	$this->assertTrue($coach->isCoach());
        	$this->assertTrue($coach->approved);

        	Mail::assertQueued(CoachCreatedEmail::class, 1);
        	Mail::assertQueued(CoachCreatedEmail::class, function($mail) use ($coach) {
        		return $mail->coach->id == $coach->id &&
        		$mail->hasTo($coach->email);
        	});
        });
    }
}