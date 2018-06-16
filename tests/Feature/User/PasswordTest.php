<?php

namespace Tests\Feature\Jockey;

use App\Models\Jockey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_jockey_can_change_their_password()
    {
    	// go to profile/password
    	// send old password, new password, new password_confirm
    	// redirect back to profile on success
    	
    	$jockey = factory(Jockey::class)->create([
    		'password' => Hash::make('old-password')
    	]);

    	$oldPassword = $jockey->password;

    	$response = $this->actingAs($jockey)->from('/profile/password')->put('/profile/password', [
    		'old_password' => 'old-password',
    		'password' => 'new-password',
    		'password_confirmation' => 'new-password',
    	]);

    	$response->assertStatus(302);
        $response->assertRedirect('/profile');

        tap($jockey->fresh(), function($jockey) use ($oldPassword) {
        	$this->assertNotEquals($oldPassword, $jockey->password);
        	$this->assertTrue(Hash::check('new-password', $jockey->password));
        });
    }

    /** @test */
    public function confirm_password_must_equal_the_password()
    {
        $jockey = factory(Jockey::class)->create([
    		'password' => Hash::make('old-password')
    	]);

    	$oldPassword = $jockey->password;

    	$response = $this->actingAs($jockey)->from('/profile/password')->put('/profile/password', [
    		'old-password' => 'old-password',
    		'password' => 'new-password',
    		'password-confirm' => 'somethingelse',
    	]);

    	$response->assertStatus(302);
    	$response->assertRedirect('/profile/password');
    	$response->assertSessionHasErrors('password');

    	 // 'password' => 'required|confirmed|min:6',

    	tap($jockey->fresh(), function($jockey) use ($oldPassword) {
        	$this->assertEquals($oldPassword, $jockey->password);
        	$this->assertTrue(Hash::check('old-password', $jockey->password));
        });
    }

    /** @test */
    public function the_old_password_must_equal_the_current_password()
    {
        $jockey = factory(Jockey::class)->create([
    		'password' => Hash::make('old-password')
    	]);

    	$oldPassword = $jockey->password;

    	$response = $this->actingAs($jockey)->from('/profile/password')->put('/profile/password', [
    		'old-password' => 'somethingelse',
    		'password' => 'new-password',
    		'password-confirm' => 'new-password',
    	]);

    	$response->assertStatus(302);
    	$response->assertRedirect('/profile/password');
    	$response->assertSessionHasErrors('old_password');

    	tap($jockey->fresh(), function($jockey) use ($oldPassword) {
        	$this->assertEquals($oldPassword, $jockey->password);
        	$this->assertTrue(Hash::check('old-password', $jockey->password));
        });
    }
}