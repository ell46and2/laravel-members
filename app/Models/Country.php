<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	public $timestamps = false;

    public function counties()
    {
    	return $this->hasMany(County::class);
    }
}
