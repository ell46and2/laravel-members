<?php

namespace App\Jobs\Comment;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyNewComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $commentable = $this->comment->commentable;

        $body = "There is a new comment from {$this->comment->author->full_name} on {$commentable->formattedCommentName}";

        $commentable->notifications()->create([
            'user_id' => $this->comment->recipient_id,
            'body' => $body
        ]);
    }
}
