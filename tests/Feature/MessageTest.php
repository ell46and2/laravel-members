<?php

namespace Tests\Feature;

use App\Models\Coach;
use App\Models\Jockey;
use App\Models\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_sent_a_message_to_another_user()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();

        $response = $this->actingAs($coach)->post("/messages", [
        	'recipient_id' => $jockey->id,
        	'subject' => 'Message subject',
        	'body' => 'This is the message body', // may need to include links, bold etc
        ]);

        tap(Message::first(), function($message) use ($response, $jockey, $coach) {
            $response->assertStatus(302);
            $response->assertRedirect("/messages");

            // Do we need to show a flash message saying message sent?
          

            $this->assertEquals('Message subject', $message->subject);
            $this->assertEquals('This is the message body', $message->body);
            $this->assertEquals($jockey->id, $message->recipient_id);
            $this->assertEquals($coach->id, $message->author_id);
            $this->assertEquals(false, $message->read);

            $this->assertEquals($jockey->messages()->count(), 1);
        });
    }


    /** @test */
    public function a_user_can_only_see_messages_of_which_they_are_the_recipient()
    {
    	$jockey = factory(Jockey::class)->create();

    	$messages = factory(Message::class, 10)->create([
    		'recipient_id' => $jockey->id
    	]);

    	$messagesNotForJockey = factory(Message::class, 5)->create();

    	$this->assertEquals(Message::all()->count(), 15);

    	$this->assertEquals($jockey->messages()->count(), 10);
    }

    /** @test */
    public function a_user_can_mark_a_message_as_read() // Or do they get marked as read when viewed
    {
     //    $jockey = factory(Jockey::class)->create();

    	// $message = factory(Message::class)->create([
    	// 	'recipient_id' => $jockey->id
    	// ]);

    	// $response = $this->actingAs($coach)->post("/messages/{$message}", [
    }
}