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

        // return the actual thumbnail here.
    }
}
