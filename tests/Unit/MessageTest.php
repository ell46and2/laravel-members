<?php

namespace Tests\Unit;

use App\Models\Coach;
use App\Models\Jockey;
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
		$jockey = factory(Jockey::class)->create();
	    $message = factory(Message::class)->create();

	    $message->addRecipient($jockey);

	    // dd($jockey->messages->first()->pivot->read);

	    $this->assertEquals(0, $jockey->messages->first()->pivot->read);
	    $this->assertEquals(1, $jockey->messages->count());
	    $this->assertEquals(1, $jockey->unreadMessages->count());
	    $this->assertEquals(1, $jockey->fresh()->unreadMessagesCount()); 


	    $jockey->markMessageAsRead($message);


		$this->assertEquals(1, $jockey->fresh()->messages->first()->pivot->read);
		$this->assertEquals(1, $jockey->fresh()->messages->count());
	    $this->assertEquals(0, $jockey->fresh()->unreadMessages->count());
	    $this->assertEquals(0, $jockey->fresh()->unreadMessagesCount()); 
	}
}