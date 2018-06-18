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

    public function attachable()
	{
		return $this->morphTo();
	}
}
