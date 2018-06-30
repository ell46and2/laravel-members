<?php

namespace App\Jobs\RacingExcellence;

use App\Models\RacingExcellence;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyRemovedFromRacingExcellence implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jockeyId;
    public $racingExcellence;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($jockeyId, RacingExcellence $racingExcellence)
    {
        $this->jockeyId = $jockeyId;
        $this->racingExcellence = $racingExcellence;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $racingExcellence = $this->participant->racingExcellence;

        $body = "You have been removed from Racing Excellence on {$racingExcellence->start->format('l jS \\of F Y h:i A')}";
   
        $racingExcellence->notifications()->create([
            'user_id' => $this->jockeyId,
            'body' => $body
        ]);
    }
}
