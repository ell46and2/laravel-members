<?php

namespace Tests\Feature\Jockey;

use App\Models\Jockey;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginJockeyTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function a_registered_jockey_not_yet_approved_cannot_login()
    {
        $jockey = factory(Jockey::class)->create([
        	'email' => 'jane@example.com',
            'password' => bcrypt('super-secret-password')
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'super-secret-password'
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $errors = session('errors');
        $this->assertEquals($errors->get('email')[0],'No account found, or your account is awaiting approval.');

        $this->assertFalse(Auth::check()); 
    }

    /** @test */
    public function a_registered_jockey_thats_approved_can_login()
    {
        $jockey = factory(Jockey::class)->states('approved')->create([
        	'email' => 'jane@example.com',
            'password' => bcrypt('super-secret-password')
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'super-secret-password'
        ]);

        $response->assertRedirect('/dashboard');
        
        $this->assertTrue(Auth::user()->is($jockey));
    }

    /** @test */
    public function logging_in_with_invalid_credentials()
    {
        $jockey = factory(Jockey::class)->states('approved')->create([
        	'email' => 'jane@example.com',
            'password' => bcrypt('super-secret-password')
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'not-the-right-password'
        ]);

        $response->assertRedirect('/login');
        // check that the session has an error (laravel sets error for email if password and/or email is incorrect).
        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::check());

        // check that the old input email is prepopulated in the form.
        $this->assertTrue(session()->hasOldInput('email'));

        // check that the old input password is NOT prepopulated in the form.
        $this->assertFalse(session()->hasOldInput('password'));
    }
}