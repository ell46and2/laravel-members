<?php

namespace App\Jobs\RacingExcellence;

use App\Models\Admin;
use App\Models\RacingExcellence;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyAdminNewRE implements ShouldQueue
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
        $admins = Admin::all();

        $body = "New Racing Excellence added. Please assign the Coach.";

        $racingExcellence = $this->racingExcellence;

        $admins->each(function($admin) use ($body, $racingExcellence) {
            $racingExcellence->notifications()->create([
                'user_id' => $admin->id,
                'body' => $body
            ]);
        });
    }
}
