<?php

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttachmentResource;
use App\Jobs\UploadImage;
use App\Jobs\UploadVideo;
use App\Models\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
	// NOTE: Add form request - validate file is either an image or video
    public function store(Request $request) 
    {
    	// generate uid
    	// get the attachable model - ie an Activity
    	// NOTE: do we need to check that attachable type exists?
    	$attachment = Attachment::create([
    		'uid' => $uid = uniqid(true),
    		'filename' => "{$uid}.{$request->fileExtension}", 
    		'attachable_type' => $request->modelType,
    		'attachable_id' => $request->modelId,
    		'filetype' => $fileType = getFileType($request->file('attachment'))
    	]);

    	$request->file('attachment')->move(storage_path() . '/uploads', $attachment->filename);

    	if($fileType === 'video') {
    		$this->dispatch(new UploadVideo($attachment->filename));
    	} else {
    		// dispatch uploadimage job
            $this->dispatch(new UploadImage($attachment)); 
    		// Create thumbnail too.
    	}
        
        return new AttachmentResource($attachment);
    }

    public function show(Attachment $attachment)
    {
        return new AttachmentResource($attachment);
    }

    public function destroy(Attachment $attachment)
    {
        $this->authorize('delete', $attachment);

        $attachment->delete();
    }
}
