<?php

namespace Tests\Feature\Jockey;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_jockey_can_receive_a_message()
    {
        // subject, body, from full name, date - as days ago
    }

    /** @test */
    public function a_jockey_can_mark_a_message_as_read()
    {
        	
    }

    /** @test */
    public function a_jockey_can_sent_a_message_to_a_coach()
    {
        	
    }
}