<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
	use SoftDeletes;

	protected $guarded = ['id'];

	public function getRouteKeyName()
	{
		return 'uid';
	}

	/*
		Relationships
	*/
    public function attachable()
	{
		return $this->morphTo();
	}

	/*
		Utilities
	*/
	public function getThumb()
    {
        if (!$this->processed) {
        	// NOTE: need to return different placeholders for video and images.
            return config('jcp.buckets.avatars') . 'default_avatar.png';
        }

        if($this->filetype === 'video') {
        	return config('jcp.buckets.video_thumbnails') . $this->uid . '_thumb_00001.jpg';
        }

        // return the actual thumbnail here.
    }

    public function getStreamUrl()
    {
    	return config('jcp.buckets.videos_out') . $this->uid . '.mp4';
    }
}