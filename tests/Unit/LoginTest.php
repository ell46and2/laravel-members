<?php

namespace Tests\Unit;

use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function on_successful_login_the_last_login_field_is_timestamped()
	{
	    $jockey = factory(Jockey::class)->states('approved')->create([
	    	'email' => 'jane@example.com',
	    	'password' => bcrypt('secret'),
	    ]);

	    $this->assertNull($jockey->last_login);

	    $response = $this->post("/login", [
	    	'email' => 'jane@example.com',
	    	'password' => 'secret',
	    ]);

	    tap($jockey->fresh(), function($jockey) use ($response) {
        	$response->assertStatus(302);

        	$this->assertNotNull($jockey->last_login);
        });
	}
}