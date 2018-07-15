<?php

namespace Tests\Feature\Coach;

use App\Models\Coach;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class TokenAccessTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_newly_created_coach_can_login_to_the_system_via_their_activation_token()
	{
		$coach = factory(Coach::class)->states('with_token')->create();

		$response = $this->get("coach/auth?token={$coach->access_token}&email={$coach->email}");

		$response->assertStatus(200);
	}

	/** @test */
	public function a_newly_created_coach_cannot_login_to_the_system_with_an_invalid_activation_token()
	{
	    $coach = factory(Coach::class)->states('with_token')->create();

	    $notAccessToken = str_random(100);

	    $response = $this->get("coach/auth?token={$notAccessToken}&email={$coach->email}");

	    $response->assertStatus(302);
        $response->assertRedirect("/login");
	    $this->assertFalse(auth()->check()); 
	}

	/** @test */
	public function an_access_token_is_required()
	{
	    $coach = factory(Coach::class)->create();

	    $response = $this->get("coach/auth?token=&email={$coach->email}");

	    $response->assertStatus(302);
        $response->assertRedirect("/login");
	    $this->assertFalse(auth()->check()); 

	}

	/** @test */
	public function the_email_and_token_must_be_on_the_same_coach()
	{
	    $coach1 = factory(Coach::class)->states('with_token')->create();
	    $coach2 = factory(Coach::class)->states('with_token')->create();

	    $response = $this->get("coach/auth?token={$coach1->access_token}&email={$coach2->email}");

	    $response->assertStatus(302);
        $response->assertRedirect("/login");
	    $this->assertFalse(auth()->check()); 
	}
}
