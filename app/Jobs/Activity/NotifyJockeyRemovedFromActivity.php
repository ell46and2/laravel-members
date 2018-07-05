<?php

namespace App\Jobs\Activity;

use App\Models\Activity;
use App\Models\Jockey;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyJockeyRemovedFromActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $activity;
    public $jockey;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Activity $activity, Jockey $jockey)
    {
        $this->activity = $activity;
        $this->jockey = $jockey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $body = "You have been removed from the {$this->activity->formattedType} activity on {$this->activity->start->format('l jS \\of F Y h:i A')}";
     
        Notification::create([
            'user_id' => $this->jockey->id,
            'body' => $body
        ]);
    }
}
