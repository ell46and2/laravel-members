<?php

namespace App\Jobs\SkillProfile;

use App\Models\SkillProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatedNotifyJockey implements ShouldQueue
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
        $body = "Skills Profile updated.";

        $this->skillProfile->notifications()->create([
            'user_id' => $this->skillProfile->jockey_id,
            'body' => $body
        ]);
    }
}
