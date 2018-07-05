<?php

namespace App\Jobs\Activity;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCoachNewActivity implements ShouldQueue
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
        $body = "You have a new {$this->activity->formattedType} activity on {$this->activity->start->format('l jS \\of F Y h:i A')}";
     
        $this->activity->notifications()->create([
            'user_id' => $this->activity->coach_id,
            'body' => $body
        ]);
    }
}
