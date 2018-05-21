<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    /*
        Relationships
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function coaches()  // could order alphabetically here ->orderBy('last_name', 'desc')
    {
        return $this->belongsToMany(User::class, 'coach_jockey', 'coach_id', 'jockey_id');
    }

    public function jockeys() // could order alphabetically here ->orderBy('last_name', 'desc')
    {
        return $this->belongsToMany(User::class, 'coach_jockey', 'jockey_id', 'coach_id');
    }

    public function activities()
    {
        if($this->isCoach()) {
            return $this->hasMany(Activity::class, 'coach_id');
        }

        if($this->isJockey()) {
            return $this->belongsToMany(Activity::class, 'activity_jockey', 'jockey_id', 'activity_id');
        }
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /*
        Utilities
     */
    
    public static function admin()
    {
        return self::whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->firstOrFail();
    }

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function assignJockey(User $jockey)
    {
        $this->jockeys()->attach($jockey->id);
    }

    public function unassignJockey(User $jockey)
    {
        $this->jockeys()->detach($jockey->id);
    }

    public static function getAllCoaches()
    {
        return self::whereHas('role', function($q) {
            $q->where('name', 'coach');
        })->get();
    }

    public function isJockey()
    {
        return (bool) $this->role->name = 'jockey';
    }

    public function isCoach()
    {
        return (bool) $this->role->name = 'coach';
    }

    public static function createCoach($requestData)
    {
        $data = array_merge($requestData, [ 
            'password' => uniqid(true),
            'role_id' => Role::where('name', 'coach')->firstOrFail()->id,
            'approved' => true,
        ]);

        return self::create($data);
    }

    public function approve()
    {
        $this->update([
            'approved' => true
        ]);
    }
}
