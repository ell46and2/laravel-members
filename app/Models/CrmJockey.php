<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmJockey extends Model
{
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'date_of_birth'];

    protected $appends = ['full_name'];

    public function crmRecords()
    {
        return $this->morphMany(CrmRecord::class, 'managable');
    }

    public function getAvatar()
    {       
        return config('jcp.buckets.avatars') . 'default_avatar.png';
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getCrmTypeAttribute()
    {
        return 'crm-jockey';
    }

    public function getCrmRecordCreateLinkAttribute()
    {
        return 'jets.crm.create-with-crm';
    }

    public function getCrmRecordShowLinkAttribute()
    {
        return "/jets/crm/crm-jockey/{$this->id}";
    }
}
