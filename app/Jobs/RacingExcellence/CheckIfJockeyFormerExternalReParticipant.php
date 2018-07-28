<?php

namespace App\Jobs\RacingExcellence;

use App\Models\Jockey;
use App\Models\RacingExcellenceParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckIfJockeyFormerExternalReParticipant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jockey;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Jockey $jockey)
    {
        $this->jockey = $jockey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $participants = RacingExcellenceParticipant::where('api_id', $this->jockey->api_id)->get();

        if($participants) {
            foreach ($participants as $participant) {
                $participant->update([
                    'jockey_id' => $this->jockey->id,
                    'name' => null,
                ]);
            }
        }
    }
}
