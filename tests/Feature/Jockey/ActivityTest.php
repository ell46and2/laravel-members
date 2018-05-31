<?php

namespace Tests\Feature\Jockey;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;


   	/** @test */
    public function can_add_a_comment_to_an_activity_they_belong_to()
    {
            
    }

    /** @test */
    public function can_see_all_public_comments_from_coaches()
    {
            
    }

    /** @test */
    public function can_only_see_comments_for_them()
    {
            
    }

    /** @test */
    public function cannot_see_private_comments_from_coaches()
    {
            
    }
}