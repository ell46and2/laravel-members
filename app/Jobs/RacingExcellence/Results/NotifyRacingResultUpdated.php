<?php

namespace App\Jobs\RacingExcellence\Results;

use App\Models\RacingExcellenceParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyRacingResultUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $participant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RacingExcellenceParticipant $participant)
    {
        $this->participant = $participant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $body = 'Your results for Racing Excellence have been updated';

        $this->participant->racingExcellence->notifications()->create([
            'user_id' => $participant->jockey_id,
            'body' => $body
        ]);
    }
}
