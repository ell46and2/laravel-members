<?php

namespace App\Jobs\Activity;

use App\Models\Coach;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCoachDeletedActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $coach;
    public $activityType;
    public $activityStart;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Coach $coach, String $activityType, String $activityStart)
    {
        $this->coach = $coach;
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
        $body = "Your {$this->activityType} activity on {$this->activityStart} has been cancelled";
      
        Notification::create([
            'user_id' => $this->coach->id,
            'body' => $body
        ]);
    }
}
