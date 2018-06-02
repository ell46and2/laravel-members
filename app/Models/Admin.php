<?php

namespace App\Models;

class Admin extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {
            // $query->whereHas('role', function($q) {
            // 	$q->where('name', 'admin');
            // });
            $query->where('role_id', 3);
        });
    }
}
