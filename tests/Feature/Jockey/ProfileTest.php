<?php

namespace Tests\Feature\Jockey;

use App\Models\Jockey;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_jockey_can_view_their_account_profile()
    {
    	factory(User::class, 10)->create();
    	
        $jockey = factory(Jockey::class)->states('approved')->create([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        	'telephone' => '01242 222333',	
        	'address_1' => '123 street',
        	'address_2' => 'Cheltenham',
        	'postcode' => 'GL50 1ST',
        	'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($jockey)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('jockey.profile.index');
        $this->assertTrue($response->data('jockey')->is($jockey));
    }

    /** @test */
    public function a_jockey_can_view_the_edit_form_for_their_profile()
    {
        $jockey = factory(Jockey::class)->states('approved')->create([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        	'telephone' => '01242 222333',	
        	'address_1' => '123 street',
        	'address_2' => 'Cheltenham',
        	'postcode' => 'GL50 1ST',
        	'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($jockey)->get('/profile/edit');

        $response->assertStatus(200);
        $response->assertViewIs('jockey.profile.edit');
        $this->assertTrue($response->data('jockey')->is($jockey));
    }

    /** @test */
    public function a_jockey_can_edit_their_own_profile()
    {
        $jockey = factory(Jockey::class)->states('approved')->create([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        	'telephone' => '01242 222333',	
        	'address_1' => '123 street',
        	'address_2' => 'Cheltenham',
        	'postcode' => 'GL50 1ST',
        	'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($jockey)->put("/profile/edit", [
        	'first_name' => 'New firstname',
        	'last_name' => 'New lastname',
        	'telephone' => '0000 111111',	
        	'address_1' => 'New address',
        	'address_2' => 'New city',
        	'postcode' => 'New post',
        	'email' => 'newemail@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/profile');

        tap($jockey->fresh(), function($jockey) {
        	$this->assertEquals('New firstname', $jockey->first_name);
		    $this->assertEquals('New lastname', $jockey->last_name);
		    $this->assertEquals('0000 111111', $jockey->telephone);
		    $this->assertEquals('New address', $jockey->address_1);
		    $this->assertEquals('New city', $jockey->address_2);
		    $this->assertEquals('New post', $jockey->postcode);
		    $this->assertEquals('newemail@example.com', $jockey->email);
        });
    }
}