<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
	protected $fillable = ['document_filename', 'title'];

	protected $dates = ['created_at', 'updated_at'];

	/*
		Relationships
	*/
	public function notifications()
    {
    	return $this->morphMany(Notification::class, 'notifiable');
    }

    /*
    	Utilities
    */
    public function getDocument()
    {  
        return config('jcp.buckets.documents') . $this->document_filename;
    }
}
