<?php

namespace App\Jobs\SkillProfile;

use App\Models\SkillProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreatedNotifyJockey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $skillProfile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SkillProfile $skillProfile)
    {
        $this->skillProfile = $skillProfile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $body = "New Skills Profile created.";

        $this->skillProfile->notifications()->create([
            'user_id' => $this->skillProfile->jockey_id,
            'body' => $body
        ]);
    }
}
