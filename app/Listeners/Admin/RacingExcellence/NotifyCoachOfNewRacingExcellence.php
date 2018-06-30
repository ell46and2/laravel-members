<?php

namespace App\Listeners\Admin\RacingExcellence;

use App\Events\Admin\RacingExcellence\NewRacingExcellenceCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyCoachOfNewRacingExcellence
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
     * @param  NewRacingExcellenceCreated  $event
     * @return void
     */
    public function handle(NewRacingExcellenceCreated $event)
    {
        $coach = $event->racingExcellence->coach;

        $body = "You have been assigned to a Racing Excellence event on {$event->racingExcellence->start->format('l jS \\of F Y h:i A')}";

        $event->racingExcellence->notifications()->create([
            'user_id' => $coach->id,
            'body' => $body
        ]);
    }
}
