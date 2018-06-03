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
        'role_id',
        'first_name',
        'last_name',
        'middle_name',
        'alias',
        'date_of_birth',
        'gender',
        'address_1',
        'address_2',
        'county',
        'country',
        'postcode',
        'telephone',
        'twitter_handle',
        'email',
        'password',
        'approved',
        'avatar_path',
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

    protected $dates = ['created_at', 'updated_at', 'date_of_birth'];

    /*
        Relationships
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('read', false);
    }

    /*
        Utilities
     */
    
    // public static function admin() // Needs amending as can be more than one admin
    // {
    //     return self::whereHas('role', function($q) {
    //         $q->where('name', 'admin');
    //     })->firstOrFail();
    // }
    
    // Convert date of birth to Carbon when saving to db.
    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = Carbon::parse($value);
    }

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // public static function getAllCoaches()
    // {
    //     return self::whereHas('role', function($q) {
    //         $q->where('name', 'coach');
    //     })->get();
    // }
 
    public function isAdmin()
    {
        return $this->role->name === 'admin';
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

    public function getAvatar()
    {
        if (!$this->avatar_filename) {
            return config('jcp.buckets.avatars') . 'default_avatar.png';
        }

        return config('jcp.buckets.avatars') . $this->avatar_filename;
    }
}
