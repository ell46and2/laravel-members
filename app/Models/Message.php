<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $fillable = ['subject', 'body', 'author_id', 'recipient_id', 'read'];

	protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'read' => 'boolean'
    ];

    /*
    	Relationships
     */

   	public function recipient()
   	{
   		return $this->belongsTo(User::class, 'recipient_id');
   	}

   	public function author()
   	{
   		return $this->belongsTo(User::class, 'author_id');
   	}

   	/*
   		Utilities
   	 */
   	public function markAsRead()
   	{
   		$this->update([
            'read' => true
        ]);
   	}
}
