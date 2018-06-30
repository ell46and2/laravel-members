<?php

namespace App\Jobs\Activity;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyJockeyAmendedActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $activity;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $activity = $this->activity;

        $jockeys = $activity->jockeys;
        // If no jockeys for activity just return
        if(!$jockeys->count()) {
            return;
        }

        // for each event->activity->jockeys created a notification
        // dd($jockeys);
        $body = "Your {$event->activity->formattedType} activity has been updated";

        $jockeys->each(function($jockey) use ($body, $activity) {
            $activity->notifications()->create([
                'user_id' => $jockey->id,
                'body' => $body
            ]);
        });
    }
}
