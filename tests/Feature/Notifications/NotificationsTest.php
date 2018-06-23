<?php

namespace Tests\Feature\Notifications;

use App\Models\Coach;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_dismiss_a_notification()
    {
        $coach = factory(Coach::class)->create();

        $notification1 = factory(Notification::class)->create([
            'user_id' => $coach->id,
        ]);

        $notification2 = factory(Notification::class)->create([
            'user_id' => $coach->id,
        ]);

 		$this->assertFalse($notification1->read);
 		$this->assertFalse($notification2->read);

 		$response = $this->actingAs($coach)->put("/notification/{$notification1->id}/dismiss");

 		$response->assertStatus(200);
 		$this->assertTrue($notification1->fresh()->read);
 		$this->assertFalse($notification2->fresh()->read);	
    }

    /** @test */
    public function a_user_can_only_dismiss_their_own_notifications()
    {
        $coach = factory(Coach::class)->create();
        $otherCoach = factory(Coach::class)->create();

        $notification1 = factory(Notification::class)->create([
            'user_id' => $coach->id,
        ]);

 		$this->assertFalse($notification1->read);

 		$response = $this->actingAs($otherCoach)->put("/notification/{$notification1->id}/dismiss");

 		// $response->assertStatus(200);
 		$this->assertFalse($notification1->fresh()->read);
    }

    /** @test */
    public function can_mark_all_as_read()
    {
        $coach = factory(Coach::class)->create();

        $notification1 = factory(Notification::class)->create([
            'user_id' => $coach->id,
        ]);

        $notification2 = factory(Notification::class)->create([
            'user_id' => $coach->id,
        ]);

        $notificationForOtherUser = factory(Notification::class)->create([]);

        $futureNotification = factory(Notification::class)->create([
            'user_id' => $coach->id,
            'created_at' => Carbon::now()->addMinutes(1)
        ]);

        $this->assertFalse($notification1->read);
 		$this->assertFalse($notification2->read);
 		$this->assertFalse($notificationForOtherUser->read);
 		$this->assertFalse($futureNotification->read);

 		$response = $this->actingAs($coach)->post("/notification/{$coach->id}/dismiss-all", [
 			'from' => Carbon::now()->toDateTimeString()
 		]);

 		$this->assertTrue($notification1->fresh()->read);
 		$this->assertTrue($notification2->fresh()->read);
 		$this->assertFalse($notificationForOtherUser->fresh()->read);
 		$this->assertFalse($futureNotification->fresh()->read);
    }

    /** @test */
    public function a_user_can_only_mark_all_as_read_on_their_own_notifications()
    {
        $coach = factory(Coach::class)->create();
        $otherCoach = factory(Coach::class)->create();

        $notification1 = factory(Notification::class)->create([
            'user_id' => $coach->id,
        ]);

        $notification2 = factory(Notification::class)->create([
            'user_id' => $coach->id,
        ]);

        $this->assertFalse($notification1->read);
 		$this->assertFalse($notification2->read);

 		$response = $this->actingAs($otherCoach)->post("/notification/{$coach->id}/dismiss-all", [
 			'from' => Carbon::now()->toDateTimeString()
 		]);

 		$this->assertFalse($notification1->fresh()->read);
 		$this->assertFalse($notification2->fresh()->read);

    }
}