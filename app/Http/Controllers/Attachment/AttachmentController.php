<?php

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\Controller;
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
    		// dispatch uploadvideo job
    	} else {
    		// dispatch uploadimage job
    		// Create thumbnail too.
    	}

    	// NOTE: set processed to true once image is uploaded to s3
    	// NOTE: set processed to true for video once its been transcoded.
    	// NOTE: display placeholder thumbnail until processed equals true. 
    }
}
