<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Notification extends Model
{
	protected $fillable = ['user_id', 'body', 'read'];

	protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'read' => 'boolean'
    ];

    /*
        Relationships
     */

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function notifiable()
	{
		return $this->morphTo();
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

    public static function markAllAsRead(Collection $notifications)
    {
        self::whereIn('id', $notifications->pluck('id'))->update([
            'read' => true
        ]);
    }

    public function linkUrl()
    {
        if(!$this->notifiable) {
            return null;
        }

        // May need to append with 'coach', 'admin' depending on users role.

        // notifiable_type / notifiable->id  i.e activity/2
        return "{$this->notifiable_type}/{$this->notifiable->id}";
    }
}
