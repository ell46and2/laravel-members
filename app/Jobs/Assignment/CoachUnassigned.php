<?php

namespace App\Jobs\Assignment;

use App\Models\Coach;
use App\Models\Jockey;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CoachUnassigned implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jockey;
    public $coach;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Coach $coach, Jockey $jockey)
    {
        $this->coach = $coach;
        $this->jockey = $jockey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::create([
            'user_id' => $this->coach->id,
            'body' => "You have been unassigned from Jockey {$this->jockey->full_name}."
        ]);

        Notification::create([
            'user_id' => $this->jockey->id,
            'body' => "You have been unassigned from Coach {$this->coach->full_name}."
        ]);
    }
}
