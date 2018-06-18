<?php

namespace Tests\Unit;


use App\Models\Activity;
use App\Models\Coach;
use App\Models\Comment;
use App\Models\Jockey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function can_get_the_comments_for_an_activity_that_are_from_or_by_the_current_jockey_user()
	{
	    $coach = factory(Coach::class)->create();
	    $jockey = factory(Jockey::class)->create();
	    auth()->loginUsingId($jockey->id);
	    $otherJockey = factory(Jockey::class)->create();

	    $activity = factory(Activity::class)->create();
	    $otherActivity = factory(Activity::class)->create();

	    // comment by coach for jockey
	    // 'commentable_id' => function() {
		$coachCommentForJockeyA = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
		]);
		$coachCommentForJockeyB = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
		]);   
	    
	    // comment by jockey
	    $jockeyCommentForCoachA = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $jockey->id,
			'recipient_id' => $coach->id,
		]);
		$jockeyCommentForCoachB = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $jockey->id,
			'recipient_id' => $coach->id,
		]);   
	    
	    // comment by coach for other jockey
	    $coachCommentForOtherJockey = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $otherJockey->id,
		]);
	    
	    // comment by other jockey
	    $otherJockeyCommentForCoach = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $otherJockey->id,
			'recipient_id' => $coach->id,
		]);
	    
		// comment for other activity
		$coachCommentForJockeyOnOtherActivity = factory(Comment::class)->create([
			'commentable_id' => $otherActivity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
		]);

		$jockeyCommentForCoachOnOtherActivity = factory(Comment::class)->create([
			'commentable_id' => $otherActivity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
		]);

		$this->assertEquals(4, $activity->commentsForOrFromJockey->count());
		$activity->commentsForOrFromJockey->assertContains($coachCommentForJockeyA);
		$activity->commentsForOrFromJockey->assertContains($coachCommentForJockeyB);
		$activity->commentsForOrFromJockey->assertContains($jockeyCommentForCoachA);
		$activity->commentsForOrFromJockey->assertContains($jockeyCommentForCoachB);

		$activity->commentsForOrFromJockey->assertNotContains($coachCommentForOtherJockey);
		$activity->commentsForOrFromJockey->assertNotContains($otherJockeyCommentForCoach);
		$activity->commentsForOrFromJockey->assertNotContains($coachCommentForJockeyOnOtherActivity);
		$activity->commentsForOrFromJockey->assertNotContains($jockeyCommentForCoachOnOtherActivity);
	}

	/** @test */
	public function can_see_if_current_user_has_any_unread_comments_on_the_activity()
	{
		$jockey = factory(Jockey::class)->create();
		auth()->loginUsingId($jockey->id);
		$otherJockey = factory(Jockey::class)->create();
		$coach = factory(Coach::class)->create();
		$activity = factory(Activity::class)->create();
		$otherActivity = factory(Activity::class)->create();
		
		// add 2 unread comment for current user to activity
		$unreadCommentA = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
			'read' => false,
		]);

		$unreadCommentB = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
			'read' => false,
		]);


		// add 1 read comment for current user to activity
		$readComment = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
			'read' => true,
		]);

		// add 1 uread comment from current user to activity
		$unreadCommentFromJockey = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $jockey->id,
			'recipient_id' => $coach->id,
			'read' => false,
		]);

		// add 1 read comment from current user to activity
		$readCommentFromJockey = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $jockey->id,
			'recipient_id' => $coach->id,
			'read' => true,
		]);

		// add 1 unread comment for current user to another activity
		$unreadCommentForOtherActivity = factory(Comment::class)->create([
			'commentable_id' => $otherActivity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
			'read' => false,
		]);

		// add 1 unread comment for other user to activity
		$unreadCommentForOtherJockey = factory(Comment::class)->create([
			'commentable_id' => $activity->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $otherJockey->id,
			'read' => false,
		]);

		$this->assertTrue($activity->isThereUnreadCommentsOnActivityForCurentUser());

		// dd($activity->unreadCommentsOnActivityForCurentUser);
		$this->assertEquals($activity->unreadCommentsOnActivityForCurentUser->count(), 2);
		$activity->unreadCommentsOnActivityForCurentUser->assertContains($unreadCommentA);
		$activity->unreadCommentsOnActivityForCurentUser->assertContains($unreadCommentB);

		$activity->unreadCommentsOnActivityForCurentUser->assertNotContains($readComment);
		$activity->unreadCommentsOnActivityForCurentUser->assertNotContains($unreadCommentFromJockey);
		$activity->unreadCommentsOnActivityForCurentUser->assertNotContains($readCommentFromJockey);
		$activity->unreadCommentsOnActivityForCurentUser->assertNotContains($unreadCommentForOtherActivity);
		$activity->unreadCommentsOnActivityForCurentUser->assertNotContains($unreadCommentForOtherJockey);

		$activityWithNoUnreadComments = factory(Activity::class)->create();

		
		factory(Comment::class)->create([
			'commentable_id' => $activityWithNoUnreadComments->id,
			'commentable_type' => 'activity',
			'author_id' => $coach->id,
			'recipient_id' => $jockey->id,
			'read' => true,
		]);

		$this->assertFalse($activityWithNoUnreadComments->isThereUnreadCommentsOnActivityForCurentUser());
	}
}