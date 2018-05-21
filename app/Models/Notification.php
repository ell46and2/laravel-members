<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $fillable = ['user_id', 'body', 'read'];

	protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'read' => 'boolean'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function notifiable()
	{
		return $this->morphTo();
	}
}
