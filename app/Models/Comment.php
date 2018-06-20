<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body', 'author_id', 'recipient_id', 'private', 'read'];

    protected $casts = [
    	'read' => 'boolean',
    	'private' => 'boolean',
    ];

    public function author()
    {
    	return $this->belongsTo(User::class, 'author_id');
    }

    public function recipient()
    {
    	return $this->belongsTo(User::class, 'recipient_id');
    }

    public function commentable()
	{
		return $this->morphTo();
	}

	public function attachment() // can only have one attachment
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }
}
