<?php

namespace App\Jobs\Jockey;

use App\Models\Jockey;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCoachesOfJockeyStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jockey;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Jockey $jockey)
    {
        $this->jockey = $jockey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $coaches = $this->jockey->coaches;

        if(!$coaches) {
            return;
        }

        $body = "Your jockey {$this->jockey->full_name} status has been set to {$this->jockey->status}.";

        foreach ($coaches as $coach) {
            Notification::create([
                'user_id' => $coach->id,
                'body' => $body
            ]);
        }
    }
}
