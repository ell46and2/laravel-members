<?php

namespace App\Jobs\Pdp;

use App\Models\Pdp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyJockeyApproved implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pdp;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pdp $pdp)
    {
        $this->pdp = $pdp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $body = "Your pdp {$this->pdp->name} has been approved.";

        $this->pdp->notifications()->create([
            'user_id' => $this->pdp->jockey_id,
            'body' => $body
        ]);
    }
}
