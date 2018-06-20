<?php

namespace Tests\Feature\Jockey;

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
    public function jockey_can_see_all_comments_excluding_private_to_or_by_them()
    {
    	$coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$otherJockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);
    	$coach->assignJockey($otherJockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	$otherActivity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

    	$commentByCoach = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
            'body' => 'coach comment body',
            'private' => false,
        ]);

        $commentByPrivate = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
            'body' => 'private comment body',
            'private' => true,
        ]);

        $commentByJockey = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $jockey->id,
            'recipient_id' => $coach->id,
            'body' => 'jockey comment body',
            'private' => false,
        ]);

        $commentByCoachOnOtheractivity = factory(Comment::class)->create([
            'commentable_id' => $otherActivity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $jockey->id,
            'body' => 'coach comment body',
            'private' => false,
        ]);

        $commentByJockeyOnOtheractivity = factory(Comment::class)->create([
            'commentable_id' => $otherActivity->id,
            'commentable_type' => 'activity',
            'author_id' => $jockey->id,
            'recipient_id' => $coach->id,
            'body' => 'coach comment body',
            'private' => false,
        ]);

        $commentByCoachForOtherJockey = factory(Comment::class)->create([
            'commentable_id' => $activity->id,
            'commentable_type' => 'activity',
            'author_id' => $coach->id,
            'recipient_id' => $otherJockey->id,
            'body' => 'coach comment body',
            'private' => false,
        ]);

        $response = $this->actingAs($jockey)->get("/activity/{$activity->id}/comment?jockey={$jockey->id}");

        // $response->getData()->assertContains($commentByCoach);
        $this->assertEquals(2, count($response->getData()->data));

        $this->assertEquals(1, ($response->getData()->data[0])->id);
        $this->assertEquals('coach comment body', ($response->getData()->data[0])->body);

        $this->assertEquals(3, ($response->getData()->data[1])->id);
        $this->assertEquals('jockey comment body', ($response->getData()->data[1])->body);
       
    }

   
    /** @test */
    public function can_add_a_comment_for_a_coach_to_an_activity_they_belong_to()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        $activity->addJockey($jockey);

        // post comment with - activity_id, recipient_id, author_id, body
        $response = $this->actingAs($jockey)->post("/activity/{$activity->id}/comment", [
            'body' => 'comment body',
            'recipient_id' => $coach->id
        ]);

        tap(Comment::first(), function($comment) use ($response, $coach, $jockey, $activity) {
            $response->assertStatus(201);
            
            $this->assertEquals('comment body', $comment->body);
            $this->assertEquals($activity->comments()->first(), $comment);
            $this->assertEquals($jockey->id, $comment->author->id);
            $this->assertEquals($coach->id, $comment->recipient->id);
            $this->assertFalse($comment->private);

            // Notify jockey of new comment
            Queue::assertPushed(NotifyNewComment::class, function($job) use ($response, $comment) {
                return $job->comment->id == $comment->id;
            });
        });
    }

    /** @test */
    public function cannot_add_a_comment_on_an_activity_they_do_not_belong_to()
    {
        Queue::fake();

        $coach = factory(Coach::class)->create();
        $jockey = factory(Jockey::class)->create();
        $coach->assignJockey($jockey);

        $activity = factory(Activity::class)->create([
            'coach_id' => $coach->id
        ]);

        // post comment with - activity_id, recipient_id, author_id, body
        $response = $this->actingAs($jockey)->post("/activity/{$activity->id}/comment", [
            'body' => 'comment body',
            'recipient_id' => $coach->id
        ]);

        $this->assertEquals(0, Comment::count());

        Queue::assertNotPushed(NotifyNewComment::class);   
    }

    /** @test */
    public function cannot_mark_a_comment_as_private()
    {
        Queue::fake();

    	$coach = factory(Coach::class)->create();
    	$jockey = factory(Jockey::class)->create();
    	$coach->assignJockey($jockey);

    	$activity = factory(Activity::class)->create([
    		'coach_id' => $coach->id
    	]);

        $activity->addJockey($jockey);

    	// post comment with - activity_id, recipient_id, author_id, body
    	$response = $this->actingAs($jockey)->post("/activity/{$activity->id}/comment", [
    		'body' => 'comment body',
    		'recipient_id' => $coach->id,
    		'private' => true,
    	]);

    	tap(Comment::first(), function($comment) use ($response, $coach, $jockey, $activity) {
        	$response->assertStatus(201);
        	
            $this->assertFalse($comment->private);

            Queue::assertPushed(NotifyNewComment::class, function($job) use ($response, $comment) {
                return $job->comment->id == $comment->id;
            });
    	});	
    }

}