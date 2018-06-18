<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StorePostFormRequest;
use App\Http\Requests\Comment\UpdatePutFormRequest;
use App\Http\Resources\CommentResource;
use App\Jobs\Comment\NotifyEditedComment;
use App\Jobs\Comment\NotifyNewComment;
use App\Models\Activity;
use App\Models\Comment;
use Illuminate\Http\Request;

class ActivityCommentController extends Controller
{
    public function index(Activity $activity)
    {
        return CommentResource::collection(
            $activity->comments()->with(['author'])->get()
        );
    }

    public function store(StorePostFormRequest $request, Activity $activity) // add form validation
    {
    	// NOTE: do we need a policy to check that a user belongs to the activity.
    	// Can an admin create a comment?

    	$comment = $activity->comments()->create([
    		'body' => request()->body,
    		'author_id' => request()->user()->id,
    		'recipient_id' => request()->recipient_id,
    		'private' => request()->private ?? false,
    	]);

    	if(!$comment->fresh()->private) {
    		$this->dispatch(new NotifyNewComment($comment));
    	}

    	return new CommentResource($comment);
    }

    public function update(UpdatePutFormRequest $request, Activity $activity, Comment $comment) // add form validation
    {
    	// NOTE: Add policy - check comment author is the current user or current user is an admin user
    	$this->authorize('update', $comment);

    	$comment->update([
    		'body' => request()->body,
    		'private' => request()->private ?? false,
    	]);

    	if(!$comment->private) {
    		$this->dispatch(new NotifyEditedComment($comment));
    	}

    	return response()->json(null, 200);
    }

    public function destroy(Activity $activity, Comment $comment)
    {
    	// NOTE: Add policy - check comment author is the current user or current user is an admin user
    	$this->authorize('destroy', $comment);
    	
    	$comment->delete();

    	return response()->json(null, 200);
    }
}
