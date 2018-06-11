<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $guarded = [];

    public function activities()
    {
    	return $this->hasMany(Activity::class);
    }
}
