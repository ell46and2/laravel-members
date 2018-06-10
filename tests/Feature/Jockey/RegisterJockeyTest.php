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
            'middle_name' => 'Lee',
            'alias' => 'Janey',
            'date_of_birth' => '11/06/2000',
            'gender' => 'female',   
            'address_1' => '123 street',
            'address_2' => 'Cheltenham',
            'county_id' => 1,
            'country_id' => 1,
            'nationality_id' => 1,
            'postcode' => 'GL50 1ST',
            'telephone' => '01242 222333',
            'twitter_handle' => 'jdoe',
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
        	'county_id' => 1,
            'country_id' => 1,
            'nationality_id' => 1,
        	'postcode' => 'GL50 1ST',
            'telephone' => '01242 222333',
            'twitter_handle' => 'jdoe',
        	'email' => 'jane@example.com',
        	'password' => 'super-secret-password',
        	'password_confirmation' => 'super-secret-password',
        ]);

        // dd(Jockey::first()->role);

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
            $this->assertEquals(1, $jockey->county_id);
            $this->assertEquals(1, $jockey->country_id);
            $this->assertEquals(1, $jockey->nationality_id);
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

        // Add t&C's, privacy Policy checkboxed must be checked
    }

    /** @test */
    public function first_name_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'first_name' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('first_name');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function last_name_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'last_name' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('last_name');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function middle_name_is_optional()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->from('/register')->post('/register', $this->validParams([
            'middle_name' => ''
        ]));

        tap(Jockey::firstOrFail(), function($jockey) use ($response, $admin) {
            $response->assertRedirect('/register');
            $response->assertSessionHas('registered', true);

            $this->assertNull($jockey->middle_name);
        });
    }

    /** @test */
    public function alias_is_optional()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->from('/register')->post('/register', $this->validParams([
            'alias' => ''
        ]));

        tap(Jockey::firstOrFail(), function($jockey) use ($response, $admin) {
            $response->assertRedirect('/register');
            $response->assertSessionHas('registered', true);

            $this->assertNull($jockey->alias);
        });
    }

    /** @test */
    public function date_of_birth_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'date_of_birth' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('date_of_birth');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function date_of_birth_must_be_a_valid_date()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'date_of_birth' => 'not a date'
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('date_of_birth');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function gender_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'gender' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('gender');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function address_1_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'address_1' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('address_1');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function address_2_is_optional()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->from('/register')->post('/register', $this->validParams([
            'address_2' => ''
        ]));

        tap(Jockey::firstOrFail(), function($jockey) use ($response, $admin) {
            $response->assertRedirect('/register');
            $response->assertSessionHas('registered', true);

            $this->assertNull($jockey->address_2);
        });
    }

    /** @test */
    public function county_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'county_id' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('county_id');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function country_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'country_id' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('country_id');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function nationality_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'nationality_id' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('nationality_id');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function postcode_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'postcode' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function telephone_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
            'telephone' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('telephone');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function twitter_handle_is_optional()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->from('/register')->post('/register', $this->validParams([
            'twitter_handle' => ''
        ]));

        tap(Jockey::firstOrFail(), function($jockey) use ($response, $admin) {
            $response->assertRedirect('/register');
            $response->assertSessionHas('registered', true);

            $this->assertNull($jockey->twitter_handle);
        });
    }

    /** @test */
    public function email_is_required()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
                'email' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function email_is_must_be_a_valid_email_address()
    {
        $response = $this->from('/register')->post('/register', $this->validParams([
                'email' => 'not-an-email-address'
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, Jockey::count());
    }

    /** @test */
    public function email_must_be_unique()
    {
        $Jockey = factory(Jockey::class)->create([
            'email' => 'jane@example.com'
        ]);

        $this->assertEquals(1, Jockey::count());

        $response = $this->from('/register')->post('/register', $this->validParams([
                'email' => 'jane@example.com'
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, Jockey::count());
    }
}