<?php

namespace Tests\Feature\Coach;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProfileTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_coach_can_edit_their_profile()
	{
	    // additional bio field (compared to Jockey)
	}
}