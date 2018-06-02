<?php

namespace Tests\Feature\Jockey;

use App\Models\Activity;
use App\Models\Coach;
use App\Models\Comment;
use App\Models\Jockey;
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
        $jockey = factory(Jockey::class)->create();
        $coach = factory(Coach::class)->create();
        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);
        $activity->addJockey($jockey);

        $response = $this->actingAs($jockey)->post("/activity/{$activity->id}/comment", [
            'body' => 'This is a comment',
            'recipient_id' => $coach->id
        ]);

        tap(Comment::first(), function($comment) use ($response, $jockey, $activity) {
            // $response->assertStatus(302);
            // $response->assertRedirect("/admin/coaches/{$coach->id}");

            $this->assertEquals('This is a comment', $comment->body);
            $this->assertEquals($activity->comments()->first(), $comment);
            $this->assertEquals($jockey->id, $comment->author->id);

            // notify coach of new comment on activity
        });
    }

    /** @test */
    public function can_see_all_public_comments_from_coaches()
    {
        $jockey = factory(Jockey::class)->create();
        $coach = factory(Coach::class)->create();
        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        $comment = factory(Comment::class)->create([
            'activity_id' => $activity->id,
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id
        ]);

        // dd($comment);

        // on going to activity/{$activity->id} jockey can see the public comments from the coach
        // and their own comments
        $response = $this->actingAs($jockey)->get("/activity/{$activity->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function can_only_see_comments_for_them()
    {
        $jockey = factory(Jockey::class)->create();
        $otherJockey = factory(Jockey::class)->create();
        $coach = factory(Coach::class)->create();
        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);
        $activity->addJockey($jockey);
        $activity->addJockey($otherJockey);


    }

    /** @test */
    public function cannot_see_private_comments_from_coaches()
    {
            
    }

    /** @test */
    public function can_edit_their_own_comment()
    {
            
    }

    /** @test */
    public function cannot_edit_another_users_comment()
    {
            
    }

    /** @test */
    public function can_delete_their_own_comment()
    {
            
    }

    /** @test */
    public function cannot_delete_another_users_comment()
    {
            
    }
}