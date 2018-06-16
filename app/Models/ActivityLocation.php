<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLocation extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function activities()
    {
    	return $this->hasMany(Activity::class);
    }
}
