<?php

namespace Tests\Feature\Coach;

use App\Jobs\Comment\NotifyEditedComment;
use App\Models\Activity;
use App\Models\Coach;
use App\Models\Comment;
use App\Models\Jockey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ActivityCommentEditTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_edit_a_comment_they_have_written()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

        $comment = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
            'body' => 'comment body',
            'private' => true,
        ]);

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($coach)->post("/comment/{$comment->id}", [
    		'body' => 'NEW comment body',
            'private' => false

    	]);

    	tap($comment->fresh(), function($comment) use ($response, $coach, $jockey, $activity) {
        	$response->assertStatus(200);
        	
        	$this->assertEquals('NEW comment body', $comment->body);
            $this->assertFalse($comment->private);

            // Notify jockey of new comment
            Queue::assertPushed(NotifyEditedComment::class, function($job) use ($response, $comment) {
                return $job->comment->id == $comment->id;
            });
    	});
    }

    /** @test */
    public function cannot_edit_a_comment_they_have_not_written()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $otherCoach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        $comment = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
            'body' => 'comment body',
            'private' => true,
        ]);

        // post comment with - activity_id, recipient_id, author_id, body
        $response = $this->actingAs($otherCoach)->post("/comment/{$comment->id}", [
            'body' => 'NEW comment body',
            'private' => false

        ]); 

        tap($comment->fresh(), function($comment) use ($response, $coach, $jockey, $activity) {   
            // $response->assertStatus(302);
            
            $this->assertEquals('comment body', $comment->body);
            $this->assertTrue($comment->private);

            // Don't notify jockey if private comment.
            Queue::assertNotPushed(NotifyEditedComment::class);
        });
    }

    /** @test */
    public function if_comment_is_private_notification_is_not_sent()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        $comment = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
            'body' => 'comment body',
            'private' => false,
        ]);

        // post comment with - activity_id, recipient_id, author_id, body
        $response = $this->actingAs($coach)->post("/comment/{$comment->id}", [
            'body' => 'comment body',
            'private' => true

        ]);

    	tap($comment->fresh(), function($comment) use ($response, $coach, $jockey, $activity) {
        	$response->assertStatus(200);
        	
            $this->assertTrue($comment->private);

            // Don't notify jockey if private comment.
            Queue::assertNotPushed(NotifyEditedComment::class);
    	});	
    }

    /** @test */
    public function body_is_required()
    {	Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        $comment = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
            'body' => 'comment body',
            'private' => false,
        ]);

        // post comment with - activity_id, recipient_id, author_id, body
        $response = $this->actingAs($coach)->post("/comment/{$comment->id}", [
            'body' => '',
        ]);

    	tap($comment->fresh(), function($comment) use ($response, $coach, $jockey, $activity) {
            $response->assertStatus(302);
            
            $this->assertEquals('comment body', $comment->body);

            // Don't notify jockey if private comment.
            Queue::assertNotPushed(NotifyEditedComment::class);
        });
    }

    // /** @test */
    // public function private_must_be_a_boolean()
    // {
    //     Queue::fake();

    //     $coach = factory(Coach::class)->create();
    //     $jockey = factory(Jockey::class)->create();
    //     $coach->assignJockey($jockey);

    //     $activity = factory(Activity::class)->create([
    //         'coach_id' => $coach->id
    //     ]);

    //     $comment = factory(Comment::class)->create([
    //         'commentable_id' => $activity->id,
    //         'commentable_type' => 'activity',
    //         'author_id' => $coach->id,
    //         'recipient_id' => $jockey->id,
    //         'body' => 'comment body',
    //         'private' => true,
    //     ]);

    //     $response = $this->actingAs($coach)->post("/comment/{$comment->id}", [
    //         'body' => 'new comment',
    //         'private' => 'not a boolean',
    //     ]);

    //     $this->assertTrue($comment->private);

    //     Queue::assertNotPushed(NotifyEditedComment::class);            
    // }

    /** @test */
    public function can_add_an_image_attachment()
    {
        // will replace the current attachment
    }

    /** @test */
    public function can_add_a_video_attachment()
    {
        // will replace the current attachment
    }

    /** @test */
    public function can_delete_an_attachment()
    {
        
    }


    /** @test */
    public function can_delete_a_comment_they_have_written()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        $comment = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
        ]);

        // post comment with - activity_id, recipient_id, author_id, body
        $response = $this->actingAs($coach)->delete("/comment/{$comment->id}");

        $this->assertEquals(0, Comment::count());
    }

    /** @test */
    public function cannot_delete_a_comment_they_have_not_written()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $otherCoach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        $comment = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
        ]);

        $response = $this->actingAs($otherCoach)->delete("/comment/{$comment->id}");

        $this->assertEquals(1, Comment::count());
    }
}