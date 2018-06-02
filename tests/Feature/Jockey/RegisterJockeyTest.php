<?php

namespace Tests\Feature\Jockey;

use App\Mail\Admin\Account\ToAdminJockeyRegisteredEmail;
use App\Mail\Jockey\Account\JockeyRegisteredEmail;
use App\Models\Admin;
use App\Models\Jockey;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
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
        	'address_1' => '123 street',
            'address_2' => 'Something Avenue',
        	'county' => 'Cheltenham',
            'country' => 'UK',
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

    	$admin = factory(Admin::class)->create();

    	// create jockey user
    	// send email to jockey
    	// notify admin of new jockey registered
    	// Do not login redirect back (with registration success session variable) and display 'thank you message'

        $response = $this->post('/register', [
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
            'middle_name' => 'Lee',
            'alias' => 'Janey',
            'date_of_birth' => '11/06/2000',
            'gender' => 'female',	
        	'address_1' => '123 street',
            'address_2' => 'Cheltenham',
        	'county' => 'Gloucestershire',
            'country' => 'UK',
        	'postcode' => 'GL50 1ST',
            'telephone' => '01242 222333',
            'twitter_handle' => 'jdoe',
        	'email' => 'jane@example.com',
        	'password' => 'super-secret-password',
        	'password_confirmation' => 'super-secret-password',
        ]);

        // dd(Jockey::first());

        tap(Jockey::firstOrFail(), function($jockey) use ($response, $admin) {
        	$response->assertStatus(302);
        	//redirect back (with registration success session variable) and display 'thank you message'
        	$response->assertRedirect('/register');
        	$response->assertSessionHas('registered', true);

        	// check that a jockey is not logged in
        	$this->assertFalse(Auth::check());

            // dd($jockey);

        	$this->assertEquals('Jane', $jockey->first_name);
        	$this->assertEquals('Doe', $jockey->last_name);
            $this->assertEquals('Lee', $jockey->middle_name);
            $this->assertEquals('Janey', $jockey->alias);
            $this->assertEquals(Carbon::parse('2000-11-06'), $jockey->date_of_birth);
            $this->assertEquals('female', $jockey->gender);   	
        	$this->assertEquals('123 street', $jockey->address_1);
        	$this->assertEquals('Cheltenham', $jockey->address_2);
            $this->assertEquals('Gloucestershire', $jockey->county);
            $this->assertEquals('UK', $jockey->country);
        	$this->assertEquals('GL50 1ST', $jockey->postcode);
            $this->assertEquals('01242 222333', $jockey->telephone);
            $this->assertEquals('jdoe', $jockey->twitter_handle);
        	$this->assertEquals('jane@example.com', $jockey->email);

        	// jockey has the role 'jockey'
        	$this->assertEquals('jockey', $jockey->role->name);

        	// jockey is not approved when they register
        	$this->assertFalse($jockey->approved);

        	// Assert that registered email was queued to send to jockey
        	Mail::assertQueued(JockeyRegisteredEmail::class, 1);
        	Mail::assertQueued(JockeyRegisteredEmail::class, function($mail) use ($jockey) {
        		return $mail->jockey->id == $jockey->id &&
        		$mail->hasTo($jockey->email);
        	});

        	// Assert that new jockey registered email was queued to send to admin
        	Mail::assertQueued(ToAdminJockeyRegisteredEmail::class, 1);
        	Mail::assertQueued(ToAdminJockeyRegisteredEmail::class, function($mail) use ($jockey, $admin) {
        		return $mail->jockey->id == $jockey->id &&
        		$mail->hasTo($admin->email);
        	});
        });

        // Add Country field
        // Add t&C's, privacy Policy checkboxed
        // Add see's a thank you message on registration
    }
}