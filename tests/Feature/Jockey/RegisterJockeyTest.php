<?php

namespace Tests\Feature\Jockey;

use App\Mail\Admin\Account\ToAdminJockeyRegisteredEmail;
use App\Mail\Jockey\Account\JockeyRegisteredEmail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RegisterJockeyTest extends TestCase
{
 	use DatabaseMigrations;

 	private function validParams($overrides = [])
    {
    	return array_merge([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        	'telephone' => '01242 222333',	
        	'street_address' => '123 street',
        	'city' => 'Cheltenham',
        	'postcode' => 'GL50 1ST',
        	'email' => 'jane@example.com',
        	'password' => 'super-secret-password',
        	'password_confirmation' => 'super-secret-password',
        ], $overrides);
    }

    /** @test */
    public function a_jockey_can_register()
    {
    	Mail::fake();

    	$admin = factory(User::class)->states('admin')->create();

    	// create jockey user
    	// send email to jockey
    	// notify admin of new jockey registered
    	// Do not login redirect back (with registration success session variable) and display 'thank you message'

        $response = $this->post('/register', [
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        	'telephone' => '01242 222333',	
        	'street_address' => '123 street',
        	'city' => 'Cheltenham',
        	'postcode' => 'GL50 1ST',
        	'email' => 'jane@example.com',
        	'password' => 'super-secret-password',
        	'password_confirmation' => 'super-secret-password',
        ]);

        tap(User::find(2), function($user) use ($response, $admin) {
        	$response->assertStatus(302);
        	//redirect back (with registration success session variable) and display 'thank you message'
        	$response->assertRedirect('/register');
        	$response->assertSessionHas('registered', true);

        	// check that a user is not logged in
        	$this->assertFalse(Auth::check());

        	$this->assertEquals('Jane', $user->first_name);
        	$this->assertEquals('Doe', $user->last_name);
        	$this->assertEquals('01242 222333', $user->telephone);
        	$this->assertEquals('123 street', $user->street_address);
        	$this->assertEquals('Cheltenham', $user->city);
        	$this->assertEquals('GL50 1ST', $user->postcode);
        	$this->assertEquals('jane@example.com', $user->email);

        	// user has the role 'jockey'
        	$this->assertEquals('jockey', $user->role->name);

        	// jockey is not approved when they register
        	$this->assertFalse($user->approved);

        	// Assert that registered email was queued to send to jockey
        	Mail::assertQueued(JockeyRegisteredEmail::class, 1);
        	Mail::assertQueued(JockeyRegisteredEmail::class, function($mail) use ($user) {
        		return $mail->user->id == $user->id &&
        		$mail->hasTo($user->email);
        	});

        	// Assert that new jockey registered email was queued to send to admin
        	Mail::assertQueued(ToAdminJockeyRegisteredEmail::class, 1);
        	Mail::assertQueued(ToAdminJockeyRegisteredEmail::class, function($mail) use ($user, $admin) {
        		return $mail->user->id == $user->id &&
        		$mail->hasTo($admin->email);
        	});
        });

        // Add Country field
        // Add t&C's, privacy Policy checkboxed
        // Add see's a thank you message on registration
    }
}