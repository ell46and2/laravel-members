<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'first_name', 'last_name', 'telephone', 'street_address', 'city', 'postcode', 'role_id', 'approved'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'approved' => 'boolean' // returns 0 or 1 from db, so casts to boolean so we can assertTrue or assertFalse
    ];

    // Always eager load role as we'll use it so often.
    protected $with = [
        'role'
    ];

    /*
        Relationships
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // public function activities()
    // {
    //     // if($this->isCoach()) {
    //     //     return $this->hasMany(Activity::class, 'coach_id');
    //     // }

    //     if($this->isJockey()) {
    //         return $this->belongsToMany(Activity::class, 'activity_jockey', 'jockey_id', 'activity_id');
    //     }
    // }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('read', false);
    }

    /*
        Utilities
     */
    
    public static function admin() // Needs amending as can be more than one admin
    {
        return self::whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->firstOrFail();
    }

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public static function getAllCoaches()
    {
        return self::whereHas('role', function($q) {
            $q->where('name', 'coach');
        })->get();
    }

    public function isJockey()
    {
        return $this->role->name === 'jockey';
    }

    public function isCoach()
    {
        return $this->role->name === 'coach';
    }

    public function approve()
    {
        $this->update([
            'approved' => true
        ]);
    }
}
