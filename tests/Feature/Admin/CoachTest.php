<?php

namespace Tests\Feature\Admin;

use App\Mail\Coach\Account\CoachCreatedEmail;
use App\Models\Admin;
use App\Models\Coach;
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

     	$admin = factory(Admin::class)->create();

     	$response = $this->actingAs($admin)->post("/admin/coaches", [
        	'first_name' => 'Jane',
            'last_name' => 'Doe',
            // 'date_of_birth' => '2000-11-06',
            'gender' => 'female',   
            'address_1' => '123 street',
            'address_2' => 'Cheltenham',
            'county' => 'Gloucestershire',
            'country' => 'UK',
            'postcode' => 'GL50 1ST',
            'telephone' => '01242 222333',
            'email' => 'jane@example.com',
        ]);

        tap(Coach::first(), function($coach) use ($response) {
        	$response->assertStatus(302);
        	$response->assertRedirect("/admin/coaches/{$coach->id}");

        	$this->assertEquals('Jane', $coach->first_name);
        	$this->assertEquals('Doe', $coach->last_name);
        	$this->assertEquals('01242 222333', $coach->telephone);
        	$this->assertEquals('123 street', $coach->address_1);
        	$this->assertEquals('Cheltenham', $coach->address_2);
            $this->assertEquals('Gloucestershire', $coach->county);
            $this->assertEquals('UK', $coach->country);
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

        // Need to add creating a unique activation token, so that the coach can login straight from the activation email
        // They will be logged in to a account - set password page.
        // The page is only visible if the coach has an activation token, once the password has been set the activation token is removed.
    }
}