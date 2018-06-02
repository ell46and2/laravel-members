<?php

namespace App\Models;


class Coach extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            // $query->whereHas('role', function($q) {
            // 	$q->where('name', 'coach');
            // });
            $query->where('role_id', 2);
        });
    }

    /*
    	Relationships
    */
   
   	public function jockeys() // could order alphabetically here ->orderBy('last_name', 'desc')
    {
        return $this->belongsToMany(User::class, 'coach_jockey', 'jockey_id', 'coach_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'coach_id');
    }


    /*
        Utilities
     */

    public static function createNew($requestData)
    {
        $data = array_merge($requestData, [ 
            'password' => uniqid(true),
            'role_id' => Role::where('name', 'coach')->firstOrFail()->id,
            'approved' => true,
        ]);

        return self::create($data);
    }

    public function assignJockey(Jockey $jockey)
    {
        $this->jockeys()->attach($jockey->id);
    }

    public function unassignJockey(Jockey $jockey)
    {
        $this->jockeys()->detach($jockey->id);
    }
}
