<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmJockey extends Model
{
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'date_of_birth'];

    public function crmRecords()
    {
        return $this->morphMany(CrmRecord::class, 'managable');
    }
}
