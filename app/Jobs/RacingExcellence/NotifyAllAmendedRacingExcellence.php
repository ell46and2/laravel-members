<?php

namespace App\Jobs\RacingExcellence;

use App\Models\RacingExcellence;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyAllAmendedRacingExcellence implements ShouldQueue
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
        $jockeyParticipants = $this->racingExcellence->jockeyParticipants->unique('jockey_id');

        $body = "Your Racing Excellence event has been amended";

        $racingExcellence = $this->racingExcellence;

        $jockeyParticipants->each(function($jockeyParticipant) use ($body, $racingExcellence) {
            $racingExcellence->notifications()->create([
                'user_id' => $jockeyParticipant->jockey_id,
                'body' => $body
            ]);
        });

        $racingExcellence->notifications()->create([
            'user_id' => $racingExcellence->coach_id,
            'body' => $body
        ]);
    }
}
