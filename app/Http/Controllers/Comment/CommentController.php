<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\UpdatePutFormRequest;
use App\Http\Resources\CommentResource;
use App\Jobs\Comment\NotifyEditedComment;
use App\Jobs\UploadImage;
use App\Jobs\UploadVideo;
use App\Models\Attachment;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function update(UpdatePutFormRequest $request, Comment $comment) // form request validation
    {
        $this->authorize('update', $comment);
    	
    	// Note: need to add attachment - adding/changing/removing
        

    	
    	$comment->update([
    		'body' => request()->body,
            'private' => $this->setPrivateField(),
    	]);

        // if attachmentRemoved is true delete attachment
        if(asBoolean(request()->attachmentRemoved)) {
            $comment->attachment->delete();
        }
        
        // If file attached add as attachment
        if ($request->hasFile('attachment')) {
            $this->storeAttachment($request, $comment);
        }

        $comment = $comment->fresh();

        if(!$comment->private) {
            $this->dispatch(new NotifyEditedComment($comment));
        }

    	return new CommentResource($comment);
    }

    public function destroy(Comment $comment)
    {   
        $this->authorize('destroy', $comment);

        $attachment = $comment->attachment;
        if($attachment) {
            $attachment->delete(); 
        }
          	
    	$comment->delete();
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
            $this->dispatch(new UploadImage($attachment)); 
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
