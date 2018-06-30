<?php

namespace App\Jobs\RacingExcellence;

use App\Models\Notification;
use App\Models\RacingExcellence;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCoachRemovalRacingExcellence implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $racingExcellence;
    public $oldCoachId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RacingExcellence $racingExcellence, $oldCoachId)
    {
        $this->racingExcellence = $racingExcellence;
        $this->oldCoachId = $oldCoachId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $body = "You have been unassigned from the Racing Excellence event on {$this->racingExcellence->start->format('l jS \\of F Y h:i A')}";

        Notification::create([
            'user_id' => $this->oldCoachId,
            'body' => $body
        ]);
    }
}
