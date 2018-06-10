<?php

namespace App\Listeners\Coach\Activity;

use App\Events\Coach\Activity\NewActivityCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyJockeysOfNewActivity
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewActivityCreated  $event
     * @return void
     */
    public function handle(NewActivityCreated $event)
    {   
        $jockeys = $event->activity->jockeys;
        // If no jockeys for activity just return
        if(!$jockeys->count()) {
            return;
        }

        // for each event->activity->jockeys created a notification
        // dd($jockeys);
        $body = "You have a new [activity type] activity on {$event->activity->start->format('l jS \\of F Y h:i A')} with {$event->activity->coach->full_name}";

        $jockeys->each(function($jockey) use ($body, $event) {
            $event->activity->notifications()->create([
                'user_id' => $jockey->id,
                'body' => $body
            ]);
        });
    }
}
