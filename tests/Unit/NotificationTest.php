<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Jockey;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_get_all_unread_notifications()
    {
    	$jockey = factory(Jockey::class)->create();

        $notification1 = factory(Notification::class)->create([
        	'user_id' => $jockey->id
        ]);
        $notification2 = factory(Notification::class)->create([
        	'user_id' => $jockey->id
        ]);
        $notification3 = factory(Notification::class)->create([
        	'user_id' => $jockey->id
        ]);
        $alreadyReadNotification = factory(Notification::class)->create([
        	'user_id' => $jockey->id,
        	'read' => true
        ]);

        $unreadNotifications = $jockey->unreadNotifications()->get();

        $this->assertEquals($unreadNotifications->count(), 3);

        $unreadNotifications->assertContains($notification1);
        $unreadNotifications->assertContains($notification2);
        $unreadNotifications->assertContains($notification3);
        $unreadNotifications->assertNotContains($alreadyReadNotification);
    }

    /** @test */
    public function can_be_marked_as_read() // read/dismissed
    {
    	$jockey = factory(Jockey::class)->create();

        $notification = factory(Notification::class)->create([
        	'user_id' => $jockey->id
        ]);

        $this->assertEquals($notification->read, false);

        $notification->markAsRead();

        $this->assertEquals($notification->read, true);
    }

    /** @test */
    public function can_only_be_marked_as_read_by_the_owner_of_the_notification()
    {
        	
    }

    /** @test */
    public function can_mark_all_unread_notifications_as_read() // by clicking 'Mark all as read'
    {
        $jockey = factory(Jockey::class)->create();
        $otherJockey = factory(Jockey::class)->create();

        $notification1 = factory(Notification::class)->create([
        	'user_id' => $jockey->id
        ]);
        $notification2 = factory(Notification::class)->create([
        	'user_id' => $jockey->id
        ]);
        $notification3 = factory(Notification::class)->create([
        	'user_id' => $jockey->id
        ]);
        $alreadyReadNotification = factory(Notification::class)->create([
        	'user_id' => $jockey->id,
        	'read' => true
        ]);
        $otherJockeysNotification = factory(Notification::class)->create([
        	'user_id' => $otherJockey->id
        ]);

        $this->assertEquals($jockey->unreadNotifications()->count(), 3);

        Notification::markAllAsRead($jockey->unreadNotifications);

        $this->assertEquals($jockey->unreadNotifications()->count(), 0);
    }

    /** @test */
    public function a_notification_can_be_attached_to_a_model() // i.e an activity
    {
        // Will have a view button
        $jockey = factory(Jockey::class)->create();
        $activity = factory(Activity::class)->create();

        $notification = $activity->notifications()->create([
            'user_id' => $jockey->id,
            'body' => 'The notification body'
        ]);

        $this->assertEquals($notification->notifiable_type, 'activity');
        $this->assertEquals($notification->notifiable->id, $activity->id);
    }

    /** @test */
    public function a_notification_can_not_be_attached_to_a_model()
    {
        // Will NOT have a view button	
    }

    /** @test */
    public function on_clicking_view_will_redirect_to_the_appropriate_page_if_attached_to_a_model()
    {
        $jockey = factory(Jockey::class)->create();
        $activity = factory(Activity::class)->create();

        $notificationWithModel = $activity->notifications()->create([
            'user_id' => $jockey->id,
            'body' => 'The notification body'
        ]);

        $notificationWithoutModel = factory(Notification::class)->create([
        	'user_id' => $jockey->id
        ]);

        // May need to append with 'coach', 'admin' depending on users role.
        $this->assertEquals($notificationWithModel->linkUrl(), "activity/{$activity->id}");
        $this->assertEquals($notificationWithoutModel->linkUrl(), null);
    }

    // Do we mark the notification as read if the view button is clicked?
}