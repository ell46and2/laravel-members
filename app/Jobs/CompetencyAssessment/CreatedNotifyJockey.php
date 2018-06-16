<?php

namespace App\Jobs\CompetencyAssessment;

use App\Models\CompetencyAssessment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreatedNotifyJockey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $competencyAssessment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CompetencyAssessment $competencyAssessment)
    {
        $this->competencyAssessment = $competencyAssessment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $body = "New Competency Assessment created.";

        $this->competencyAssessment->notifications()->create([
            'user_id' => $this->competencyAssessment->jockey_id,
            'body' => $body
        ]);
    }
}
