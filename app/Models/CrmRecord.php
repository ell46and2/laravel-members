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

	public function attachment() // can only have one attachment
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }
}
