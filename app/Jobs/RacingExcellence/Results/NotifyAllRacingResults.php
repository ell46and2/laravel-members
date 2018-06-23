<?php

namespace App\Jobs\RacingExcellence\Results;

use App\Models\RacingExcellence;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyAllRacingResults implements ShouldQueue
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
        $racingExcellence = $this->racingExcellence;
        // use unique so we don't sent duplicate if same jockey appears in both divisions
        $jockeyParticipants = $racingExcellence->jockeys->unique('jockey_id');

        $body = "Your results have been added for the Racing Excellence.";

        $jockeyParticipants->each(function($jockey) use ($body, $racingExcellence) {
            $racingExcellence->notifications()->create([
                'user_id' => $jockey->id,
                'body' => $body
            ]);
        });
    }
}
