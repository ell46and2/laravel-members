<?php

namespace App\Models;

class Jet extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            // $query->whereHas('role', function($q) {
            // 	$q->where('name', 'coach');
            // });
            $query->where('role_id', 4);
        });
    }

    public function crmRecords()
    {
        return $this->hasMany(CrmRecord::class, 'jet_id');
    }

    public static function createNew($requestData)
    {
        $data = array_merge($requestData, [ 
            'password' => uniqid(true),
            'role_id' => Role::where('name', 'jets')->firstOrFail()->id,
            'approved' => true,
            'access_token' => str_random(100),
        ]);

        return self::create($data);
    }
}
