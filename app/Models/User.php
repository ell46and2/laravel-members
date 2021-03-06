<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'middle_name',
        'alias',
        'date_of_birth',
        'gender',
        'address_1',
        'address_2',
        'county_id',
        'country_id',
        'nationality_id',
        'postcode',
        'telephone',
        'twitter_handle',
        'email',
        'password',
        'approved',
        'avatar_filename',
        'vat_number',
        'last_login',
        'access_token',
        'api_id',
        'licence_type',
        'licence_date',
        'wins',
        'rides',
        'lowest_riding_weight',
        'trainer_name',
        'prize_money',
        'associated_content',
        'status',
        'bio',
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

    protected $dates = ['created_at', 'updated_at', 'date_of_birth', 'last_login', 'licence_date'];

    protected $appends = ['full_name', 'role_name'];
    /*
        Relationships
     */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('read', false)->orderBy('created_at', 'desc');
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function nationailty()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class, 'message_recipient', 'recipient_id', 'message_id')
            ->withPivot('read')->wherePivot('deleted', false);
        // return $this->hasMany(Message::class, 'recipient_id');
    }

    public function unreadMessages()
    {
        return $this->messages()->wherePivot('read', false);
    }

    public function unreadMessagesCount()
    {
        return $this->unreadMessages()->count();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'author_id')->where('deleted', false);
    }


    /*
        Utilities
     */
    public function scopeByAccessToken(Builder $builder, $email, $token)
    {
        return $builder->where('email', $email)->where('access_token', $token)->whereNotNull('access_token');
    }
      
    // Convert date of birth to Carbon when saving to db.
    public function setDateOfBirthAttribute($value)
    {
        if($value) {
           $this->attributes['date_of_birth'] = Carbon::parse($value); 
        }
    }

    public function setMileageAttribute($value)
    { 
        if($value === null) {
           $this->attributes['mileage'] = 0; 
        } else {
            $this->attributes['mileage'] = $value; 
        }
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getRoleNameAttribute()
    {
        return $this->role->name;
    }

    public function getFormattedRoleNameAttribute()
    {
        return ucfirst($this->role_name);
    }

    public function isJet()
    {
        return $this->role->name === 'jets';
    }
 
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isJockey()
    {
        return $this->role->name === 'jockey';
    }

    public function isCoach()
    {
        return $this->role->name === 'coach';
    }

    public function isCoachOrAdmin()
    {
        return $this->isCoach() || $this->isAdmin();
    }

    public function approve()
    {
        $this->update([
            'approved' => true
        ]);
    }

    public function getAvatar()
    {
        if (!$this->avatar_filename) {
            return config('jcp.buckets.avatars') . 'default_avatar.png';
        }

        return config('jcp.buckets.avatars') . $this->avatar_filename;
    }

    public function numberOfUnreadNotifications()
    {
        return $this->unreadNotifications()->count();
    }

    public function numberOfUnreadMessages()
    {
        return $this->unreadMessages()->count();
    }

    public function markMessageAsRead(Message $message)
    {
        // dd($message);
        $this->messages()->updateExistingPivot($message->id, [
            'read' => 1
        ]);
    }

    public function markMessageAsDeleted(Message $message)
    {
        $this->messages()->updateExistingPivot($message->id, [
            'deleted' => 1
        ]);
    }

    public function canBeNotified()
    {
        return $this->status !== 'gone away' || $this->status !== 'deleted';
    }


    /*
        Attributes
    */
    public function getFormattedLastLoginAttribute()
    {
        if(!$this->last_login) {
            return '-';
        }

        return $this->last_login->diffForHumans();    
    }

    public function getFormattedJoinedAttribute()
    {
        return $this->created_at->format('l jS F');
    }

    public function getFormattedLastModifiedAttribute()
    {
        return $this->updated_at->format('l jS F');
    }

    public function getFormattedGenderAttribute()
    {
        return ucfirst($this->gender);
    }

    public function getAgeAttribute()
    {
        if(!$this->date_of_birth) {
            return '-';
        }
        return $this->date_of_birth->diffInYears(now());
    }

    public function getFormattedDateOfBirthAttribute()
    {
        if(!$this->date_of_birth) {
            return '-';
        }
        return $this->date_of_birth->format('d/m/Y');
    }

    public function getFormattedCountryAttribute()
    {
        return ucfirst($this->country->name);
    }

    public function getFormattedCountyAttribute()
    {
        return $this->county->name;
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address_1;
        if($this->address_2) {
            $address = $address . '<br>' . $this->address_2;
        }
        return $address . '<br>' . $this->formattedCounty . '<br>' . $this->postcode;        
    }

    public function getFormattedTwitterHandleAttribute()
    {
        if(!$this->twitter_handle) return '-';

        return $this->twitter_handle;
    }
}
