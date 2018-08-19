<?php

namespace App\Jobs;

use App\Models\CrmRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CrmEditedNotify implements ShouldQueue
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

        $body = 'Your JETS CRM record has been updated.';

        $this->crmRecord->notifications()->create([
            'user_id' => $jockey->id,
            'body' => $body
        ]);
    }
}
