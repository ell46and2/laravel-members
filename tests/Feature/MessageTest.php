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
    public function a_coach_can_sent_a_message_to_one_of_their_assigned_jockeys()
    {
        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $response = $this->actingAs($coach)->post("/messages", [
        	'recipients' => [$jockey->id], 
        	'subject' => 'Message subject',
        	'body' => 'This is the message body', // may need to include links, bold etc
        ]);

        tap(Message::first(), function($message) use ($response, $jockey, $coach) {
            $response->assertStatus(200);

            // Do we need to show a flash message saying message sent?
          

            $this->assertEquals('Message subject', $message->subject);
            $this->assertEquals('This is the message body', $message->body);

            $this->assertEquals($coach->id, $message->author_id);

            $this->assertEquals(1, Message::all()->count());

            $this->assertEquals(1, $jockey->fresh()->unreadMessagesCount());

            // $this->assertEquals($jockey->messages()->count(), 1);
        });
    }

    /** @test */
    public function a_coach_can_sent_a_message_to_two_of_their_assigned_jockeys()
    {
        $coach = factory(Coach::class)->create();
        $jockey1 = factory(Jockey::class)->create();
        $jockey2 = factory(Jockey::class)->create();
        $coach->assignJockey($jockey1);
        $coach->assignJockey($jockey2);

        $response = $this->actingAs($coach)->post("/messages", [
            'recipients' => [$jockey1->id, $jockey2->id],
            'subject' => 'Message subject',
            'body' => 'This is the message body', // may need to include links, bold etc
        ]);

        tap(Message::first(), function($message) use ($response, $jockey1, $jockey2, $coach) {
            $response->assertStatus(200);
            // $response->assertRedirect("/messages");

            // Do we need to show a flash message saying message sent?        

            $this->assertEquals('Message subject', $message->subject);
            $this->assertEquals('This is the message body', $message->body);

            $this->assertEquals($coach->id, $message->author_id);
    

            $this->assertEquals($jockey1->messages()->count(), 1);
            $this->assertEquals($jockey1->unreadMessagesCount(), 1);

            $this->assertEquals($jockey2->messages()->count(), 1);
            $this->assertEquals($jockey2->unreadMessagesCount(), 1);
        });

        $this->assertEquals(Message::all()->count(), 1);
    }


    /** @test */
    public function a_user_can_only_see_messages_of_which_they_are_the_recipient()
    {
    	$jockey = factory(Jockey::class)->create();

    	$message1 = factory(Message::class)->create();
        $message1->addRecipient($jockey);

        $message2 = factory(Message::class)->create();
        $message2->addRecipient($jockey);

    	$messageNotForJockey = factory(Message::class)->create();

    	$this->assertEquals(Message::all()->count(), 3);

    	$this->assertEquals($jockey->messages()->count(), 2);
    }

    /** @test */
    public function a_message_is_marked_as_read_on_viewing()
    {
        $jockey = factory(Jockey::class)->create();

    	$message = factory(Message::class)->create();
        $message->addRecipient($jockey);

        $otherMessage = factory(Message::class)->create();
        $otherMessage->addRecipient($jockey);

        $this->assertEquals(2, $jockey->unreadMessagesCount());

    	$response = $this->actingAs($jockey)->get("/messages/{$message->id}");

        $this->assertEquals(1, $jockey->fresh()->unreadMessagesCount());
    }

    /** @test */
    public function a_message_must_have_a_subject()
    {
            
    }

    /** @test */
    public function a_message_must_have_a_body()
    {
            
    }

    /** @test */
    public function a_message_must_have_at_least_one_recipient()
    {
            
    }
}