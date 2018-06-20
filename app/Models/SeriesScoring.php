<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeriesScoring extends Model
{
    protected $fillable = ['series_type_id', 'place', 'points'];

    public $timestamps = false;
}
