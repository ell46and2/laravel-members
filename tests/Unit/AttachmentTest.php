<?php

namespace Tests\Unit;

use App\Models\Attachment;
use App\Models\Coach;
use App\Models\Jockey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function an_attachment_can_be_deleted()
	{
	    $coach = factory(Coach::class)->create();

	    $attachment = factory(Attachment::class)->create();

	    $this->assertEquals(1, Attachment::all()->count());

	    $response = $this->actingAs($coach)->delete("/attachment/{$attachment->uid}");

	    $this->assertEquals(0, Attachment::all()->count());
	}

	/** @test */
	public function must_be_a_coach_or_admin_to_delete_an_attachment()
	{
		$jockey = factory(Jockey::class)->states('approved')->create();

		$attachment = factory(Attachment::class)->create();

	    $this->assertEquals(1, Attachment::all()->count());

	    $response = $this->actingAs($jockey)->delete("/attachment/{$attachment->uid}");

	    $this->assertEquals(1, Attachment::all()->count());
	}
}