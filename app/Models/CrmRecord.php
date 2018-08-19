<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmRecord extends Model
{
    protected $fillable = ['jet_id', 'location', 'comment', 'review_date'];

	protected $dates = ['created_at', 'updated_at', 'review_date'];

	public function managable()
	{
		return $this->morphTo();
	}

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

	// public function attachment() // can only have one attachment
 //    {
 //        return $this->morphOne(Attachment::class, 'attachable');
 //    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function getFormattedReviewDateAttribute()
    {
        if($this->review_date) return $this->review_date->format('d/m/Y');
            
        return 'N/A';      
    }

    public function getExtensionAttribute()
    {
        $pos = strpos($this->document_filename, '.');
        return substr($this->document_filename, $pos+1);
    }

    public function getDocument()
    {  
        return config('jcp.buckets.documents') . $this->document_filename;
    }
}
