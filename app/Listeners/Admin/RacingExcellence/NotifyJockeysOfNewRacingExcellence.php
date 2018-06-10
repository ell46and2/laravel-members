<?php

namespace App\Listeners\Admin\RacingExcellence;

use App\Events\Admin\RacingExcellence\NewRacingExcellenceCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyJockeysOfNewRacingExcellence
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
        $jockeyParticipants = $event->racingExcellence->jockeys;

        $body = "You have a new Racing Excellence on {$event->racingExcellence->start->format('l jS \\of F Y h:i A')} with {$event->racingExcellence->coach->full_name}";

        $jockeyParticipants->each(function($jockeyParticipant) use ($body, $event) {
            $event->racingExcellence->notifications()->create([
                'user_id' => $jockeyParticipant->jockey_id,
                'body' => $body
            ]);
        });
    }
}
