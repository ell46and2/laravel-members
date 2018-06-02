<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body', 'author_id', 'recipient_id'];

    public function author()
    {
    	return $this->belongsTo(User::class, 'author_id');
    }
}
