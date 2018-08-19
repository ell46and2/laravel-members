<?php

namespace App\Jobs;

use App\Models\CrmRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrmCreatedNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $crmRecord;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CrmRecord $crmRecord)
    {
        $this->crmRecord = $crmRecord;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $jockey = $this->crmRecord->managable;

        $body = 'You have a new JETS CRM record.';

        $this->crmRecord->notifications()->create([
            'user_id' => $jockey->id,
            'body' => $body
        ]);
    }
}
