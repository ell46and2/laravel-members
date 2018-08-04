<?php

namespace App\Jobs\Pdp;

use App\Models\Jet;
use App\Models\Pdp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyJetsSubmitted implements ShouldQueue
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
        $body = "{$this->pdp->jockey->full_name} has submitted a Pdp to be approved.";

        foreach (Jet::all() as $jet) {
            $this->pdp->notifications()->create([
                'user_id' => $jet->id,
                'body' => $body
            ]);
        }
    }
}
