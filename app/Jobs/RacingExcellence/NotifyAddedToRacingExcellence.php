<?php

namespace App\Jobs\RacingExcellence;

use App\Models\RacingExcellenceParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyAddedToRacingExcellence implements ShouldQueue
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
        $racingExcellence = $this->participant->racingExcellence;

        $body = "You have been added to Racing Excellence on {$racingExcellence->start->format('l jS \\of F Y h:i A')} with {$racingExcellence->coach->full_name}";
   
        $racingExcellence->notifications()->create([
            'user_id' => $participant->jockey_id,
            'body' => $body
        ]);
        
    }
}
