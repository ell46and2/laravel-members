<?php

namespace App\Jobs\Activity;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyJockeyAddedToActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $activity;
    public $jockeyId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Activity $activity,$jockeyId)
    {
        $this->activity = $activity;
        $this->jockeyId = $jockeyId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->activity->start < Carbon::now()) {
            return;
        }
        
        $body = "You have a new {$this->activity->formattedType} activity on {$this->activity->start->format('l jS \\of F Y h:i A')} with {$this->activity->coach->full_name}";
     
        $this->activity->notifications()->create([
            'user_id' => $this->jockeyId,
            'body' => $body
        ]);
    }
}
