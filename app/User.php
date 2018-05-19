<?php

namespace App;

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
        'email', 'password', 'first_name', 'last_name', 'telephone', 'street_address', 'city', 'postcode', 'role_id'
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

    public function coaches()
    {
        return $this->belongsToMany(User::class, 'coach_jockey', 'coach_id', 'jockey_id');
    }

    public function jockeys()
    {
        return $this->belongsToMany(User::class, 'coach_jockey', 'jockey_id', 'coach_id');
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
}
