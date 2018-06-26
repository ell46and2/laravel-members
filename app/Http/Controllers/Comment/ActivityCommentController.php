<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StorePostFormRequest;
use App\Http\Requests\Comment\UpdatePutFormRequest;
use App\Http\Resources\CommentResource;
use App\Jobs\Comment\NotifyEditedComment;
use App\Jobs\Comment\NotifyNewComment;
use App\Jobs\UploadVideo;
use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Comment;
use Illuminate\Http\Request;

class ActivityCommentController extends Controller
{
    public function index(Activity $activity)
    {
    	$comments = $activity->commentsForOrFromJockey(request()->jockey)
    		->with(['author', 'attachment'])
    		->orderBy('created_at');

    	if(auth()->user()->isJockey()) { // don't display private comments to jockeys
    		$comments->where('private', false);
    	}

    	$commentResource = CommentResource::collection($comments->get());

    	$this->markCommentsAsRead($comments);

        return $commentResource;
    }

    public function store(StorePostFormRequest $request, Activity $activity) // add form validation
    {
    	// NOTE: do we need a policy to check that a user belongs to the activity.
    	// Can an admin create a comment? Nope
    	
    	$this->authorize('addComment', $activity);
    	
    	$comment = $activity->comments()->create([
    		'body' => request()->body,
    		'author_id' => request()->user()->id,
    		'recipient_id' => request()->recipient_id,
    		'private' => $this->setPrivateField(),
    	]);

    	// If has an attachment
    	if ($request->hasFile('attachment')) {
    		$this->storeAttachment($request, $comment);
    	}

    	if(!$comment->fresh()->private) {
    		$this->dispatch(new NotifyNewComment($comment));
    	}

    	return new CommentResource($comment);
    }
    
    private function markCommentsAsRead($comments)
    {
    	$comments->where('read', false)
    		->where('recipient_id', request()->user()->id)
    		->update(['read' => true]);
    }

    // NOTE: Look at moving the private functions below into traits so can share between
    // CommentController and ActivityCommentController.

    private function storeAttachment(Request $request, Comment $comment)
    {
    	// NOTE: look at changing filename as extension should always be
    	// jpg for images
    	// mp4 for video
    	// as we'll convert them all to this filetypes.

    	$attachment = Attachment::create([
    		'uid' => $uid = uniqid(true),
    		'filename' => "{$uid}.{$request->fileExtension}", 
    		'attachable_type' => 'comment',
    		'attachable_id' => $comment->id,
    		'filetype' => $fileType = getFileType($request->file('attachment'))
    	]);

    	$request->file('attachment')->move(storage_path() . '/uploads', $attachment->filename);

    	if($fileType === 'video') {
    		$this->dispatch(new UploadVideo($attachment->filename));
    	} else {
    		// dispatch uploadimage job
    		// Create thumbnail too.
    	}

    	// NOTE: set processed to true once image is uploaded to s3
    	// NOTE: set processed to true for video once its been transcoded.
    	// NOTE: display placeholder thumbnail until processed equals true. 
    }

    private function setPrivateField()
    {
    	if(request()->user()->isJockey()) {
    		return false;
    	}

    	return request()->private ? asBoolean(request()->private) : false;
    }
}
