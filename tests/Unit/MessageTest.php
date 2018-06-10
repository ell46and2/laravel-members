<?php

namespace Tests\Unit;

use App\Models\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_message_can_be_marked_as_read()
	{
	    $message = factory(Message::class)->create();

	    $this->assertEquals(false, $message->read);

	    $message->markAsRead();

		$this->assertEquals(true, $message->read);    
	}
}