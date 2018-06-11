<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadDocumentNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $document;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $document = $this->document;

        $body = "Document {$document->title} has been added.";

        User::chunk(50, function($users) use ($body, $document) {
            $users->each(function($user) use ($body, $document) {
                $document->notifications()->create([
                    'user_id' => $user->id,
                    'body' => $body
                ]);
            });
        });
    }
}
