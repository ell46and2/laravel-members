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
        'county_id',
        'country_id',
        'nationality_id',
        'postcode',
        'telephone',
        'twitter_handle',
        'email',
        'password',
        'approved',
        'avatar_path',
        'mileage',
        'vat_number',
        'last_login',
        'access_token'
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

    protected $dates = ['created_at', 'updated_at', 'date_of_birth'];

    protected $appends = ['full_name', 'role_name'];
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
        return $this->notifications()->where('read', false)->orderBy('created_at', 'desc');
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function nationailty()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function unreadMessages()
    {
        return $this->messages()->where('read', false);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'author_id');
    }


    /*
        Utilities
     */
      
    // Convert date of birth to Carbon when saving to db.
    public function setDateOfBirthAttribute($value)
    {
        if($value) {
           $this->attributes['date_of_birth'] = Carbon::parse($value); 
        }
    }

    public function setMileageAttribute($value)
    { 
        if($value === null) {
           $this->attributes['mileage'] = 0; 
        } else {
            $this->attributes['mileage'] = $value; 
        }
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getRoleNameAttribute()
    {
        return $this->role->name;
    }
 
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

    public function isCoachOrAdmin()
    {
        return $this->isCoach() || $this->isAdmin();
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

    public function numberOfUnreadNotifications()
    {
        return $this->unreadNotifications()->count();
    }

    public function numberOfUnreadMessages()
    {
        return $this->unreadMessages()->count();
    }
}
