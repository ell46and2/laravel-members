<?php

namespace App\Jobs\RacingExcellence;

use App\Models\RacingExcellence;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCoachAddedToRacingExcellence implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $racingExcellence;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RacingExcellence $racingExcellence)
    {
        $this->racingExcellence = $racingExcellence;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $body = "You have been assigned to the Racing Excellence event on {$this->racingExcellence->start->format('l jS \\of F Y h:i A')}. Please add the race results.";

        $this->racingExcellence->notifications()->create([
            'user_id' => $this->racingExcellence->coach_id,
            'body' => $body
        ]);
    }
}
