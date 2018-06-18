<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function update(Comment $comment) // form request validation
    {
    	// add policy to check current user is author or user is an admin
    	
    	// Note: need to add attachment - adding/changing/removing
    	
    	$comment->update([
    		'body' => request()->body
    	]);

    	return new CommentResource($comment);
    }

    public function destroy(Comment $comment)
    {
    	// Note: look at soft deleting any attachments
    	
    	$comment->delete();
    }
}
