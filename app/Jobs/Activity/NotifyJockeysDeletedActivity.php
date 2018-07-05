<?php

namespace App\Jobs\Activity;

use App\Models\Jockey;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class NotifyJockeysDeletedActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jockeyIds;
    public $activityType;
    public $activityStart;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $jockeyIds, String $activityType, String $activityStart)
    {
        $this->jockeyIds = $jockeyIds;
        $this->activityType = $activityType;
        $this->activityStart = $activityStart;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jockeys = Jockey::find($jockeyIds);
        // If no jockeys for activity just return
        if(!$jockeys->count()) {
            return;
        }

        // for each event->activity->jockeys created a notification
        // dd($jockeys);
        $body = "Your {$this->activityType} activity on {$this->activityStart} has been cancelled";

        $jockeys->each(function($jockey) use ($body) {
            Notification::create([
                'user_id' => $jockey->id,
                'body' => $body
            ]);
        });
    }
}
