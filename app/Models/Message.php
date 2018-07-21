<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $fillable = ['subject', 'body', 'author_id', 'deleted'];

	protected $dates = ['created_at', 'updated_at'];

  protected $casts = [
      'deleted' => 'boolean'
  ];

  /*
  	Relationships
   */

 	public function recipients()
 	{
    return $this->belongsToMany(User::class, 'message_recipient', 'message_id', 'recipient_id')
            ->withPivot('read');
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

  public function markAsDeleted()
  {
    $this->update([
      'deleted' => true
    ]);
  }

  public function addRecipient(User $user)
  {
    $this->recipients()->attach($user->id);
  }

  public function addRecipients(Array $userIds)
  {
    $this->recipients()->attach($userIds);
  }

  /*
    Attributes
  */

  public function getExcerptAttribute()
  {
    if(strlen($this->body) <= 50) {
      return $this->body;
    }

    return substr($this->body, 0, 50) . '...';
  }
}
