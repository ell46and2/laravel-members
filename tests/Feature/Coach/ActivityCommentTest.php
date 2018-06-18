<?php

namespace Tests\Feature\Coach;

use App\Jobs\Comment\NotifyNewComment;
use App\Models\Activity;
use App\Models\Coach;
use App\Models\Comment;
use App\Models\Jockey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ActivityCommentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_add_a_comment_for_an_individaul_jockey_to_an_activity_they_are_coach_for()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($coach)->post("/activity/{$activity->id}/comment", [
    		'body' => 'comment body',
    		'recipient_id' => $jockey->id
    	]);

    	tap(Comment::first(), function($comment) use ($response, $coach, $jockey, $activity) {
        	$response->assertStatus(200);
        	
        	$this->assertEquals('comment body', $comment->body);
            $this->assertEquals($activity->comments()->first(), $comment);
            $this->assertEquals($coach->id, $comment->author->id);
            $this->assertEquals($jockey->id, $comment->recipient->id);
            $this->assertFalse($comment->private);

            // Notify jockey of new comment
            Queue::assertPushed(NotifyNewComment::class, function($job) use ($response, $comment) {
                return $job->comment->id == $comment->id;
            });
    	});
    }

    /** @test */
    public function can_mark_a_comment_as_private()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($coach)->post("/activity/{$activity->id}/comment", [
    		'body' => 'comment body',
    		'recipient_id' => $jockey->id,
    		'private' => true,
    	]);

    	tap(Comment::first(), function($comment) use ($response, $coach, $jockey, $activity) {
        	$response->assertStatus(200);
        	
            $this->assertTrue($comment->private);

            // Don't notify jockey if private comment.
            Queue::assertNotPushed(NotifyNewComment::class);
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

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($coach)->post("/activity/{$activity->id}/comment", [
    		'body' => '',
    		'recipient_id' => $jockey->id
    	]);

    	// $response->assertStatus(302);
     //    $response->assertRedirect('/coach/competency-assessment/create');
     //    $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, Comment::count());

    	Queue::assertNotPushed(NotifyNewComment::class);
    }

    /** @test */
    public function recipient_id_is_required()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($coach)->post("/activity/{$activity->id}/comment", [
    		'body' => 'comment body',
    		'recipient_id' => ''
    	]);

    	// $response->assertStatus(302);
     	//    $response->assertRedirect('/coach/competency-assessment/create');
     	//    $response->assertSessionHasErrors('start_date');
        $this->assertEquals(0, Comment::count());

    	Queue::assertNotPushed(NotifyNewComment::class);	
    }

    /** @test */
    public function recipient_id_must_exist_on_the_users_table()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($coach)->post("/activity/{$activity->id}/comment", [
    		'body' => 'comment body',
    		'recipient_id' => 999999
    	]);

    	$this->assertEquals(0, Comment::count());

    	Queue::assertNotPushed(NotifyNewComment::class);	
    }

    /** @test */
    public function private_is_optional_and_will_default_to_false()
    {
    	Queue::fake();

    	$coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($coach)->post("/activity/{$activity->id}/comment", [
    		'body' => 'comment body',
    		'recipient_id' => $jockey->id,
    	]);

    	tap(Comment::first(), function($comment) use ($response, $coach, $jockey, $activity) {
        	$response->assertStatus(200);
        	
            $this->assertFalse($comment->private);

            // Don't notify jockey if private comment.
            Queue::assertPushed(NotifyNewComment::class);
    	});	
    }

    /** @test */
    public function private_must_be_a_boolean()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($coach)->post("/activity/{$activity->id}/comment", [
    		'body' => 'comment body',
    		'recipient_id' => $jockey->id,
    		'private' => 'Not a boolean',
    	]);

    	$this->assertEquals(0, Comment::count());

    	Queue::assertNotPushed(NotifyNewComment::class);			
    }

    /** @test */
    public function can_add_an_image_attachment()
    {
        	
    }

    /** @test */
    public function can_add_a_video_attachment()
    {
        	
    }

    /** @test */
    public function cannot_add_a_comment_for_an_activity_they_are_not_coach_for()
    {
            
    }

    /** @test */
    public function can_add_a_comment_for_all_jockeys_that_belong_to_an_activity()
    {
            
    }

    /** @test */
    public function can_view_all_comments_for_an_activity_including_other_coaches()
    {
            
    }
}