<?php

namespace App\Models;

use App\Filters\Activity\ActivityFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $fillable = ['activity_type_id', 'start', 'duration', 'end', 'location_id', 'location_name', 'group', 'coach_id'];

	protected $dates = ['created_at', 'updated_at', 'start', 'end'];

    protected $casts = [ 'group' => 'boolean' ];

	/*
		Relationships
	*/
    public function jockeys()
    {
       	return $this->belongsToMany(Jockey::class, 'activity_jockey', 'activity_id', 'jockey_id')
            ->withPivot('feedback');;
    }

	public function coach()
	{
		return $this->belongsTo(Coach::class, 'coach_id');
	}

	public function notifications()
    {
    	return $this->morphMany(Notification::class, 'notifiable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function commentsForOrFromJockey($jockeyId = null)
    {
        if($jockeyId === null) {
            $jockeyId = auth()->user()->id;
        }

        return $this->comments()->where(function($query) use($jockeyId) {
            $query->where('author_id', $jockeyId)
            ->orWhere('recipient_id', $jockeyId);
        });
    }

    public function type()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function location()
    {
        return $this->belongsTo(ActivityLocation::class, 'location_id');
    }

    public function invoiceLine()
    {
        return $this->morphOne(InvoiceLine::class, 'invoiceable');
    }

    /*
    	Utilities
    */
    public function invoicable()
    {   
        return is_null($this->invoiceLine);
    }

    public function addJockey(Jockey $jockey)
    {
    	if(!$this->jockeys()->where('jockey_id', $jockey->id)->count()) {
    		$this->jockeys()->attach($jockey);
    	}	
    }

    public function removeJockey(Jockey $jockey)
    {
    	$this->jockeys()->detach($jockey);
    }

    public function addJockeysById(Array $jockeyIds)
    {
    	// look at replacing with sync() 
    	// As sync() will remove any jockeys that are not in the array.
    	$this->jockeys()->sync($jockeyIds); 
    }

    public function addJockeyById($jockeyId)
    {
        $this->jockeys()->attach($jockeyId);
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new ActivityFilters($request))->filter($builder);
    }

    public function unreadCommentsOnActivityForCurentUser()
    {
        return $this->comments()->where(function($query) {
            $query->where('recipient_id', auth()->user()->id)
            ->where('read', false);
        });
    }

    public function isThereUnreadCommentsOnActivityForCurentUser()
    {
        return (bool) $this->unreadCommentsOnActivityForCurentUser->count();
    }

    public function isAssignedToUser(User $user)
    {
        if($this->coach_id === $user->id || $this->jockeys->pluck('id')->contains($user->id)) {
            return true;
        }

        return false;
    }

    public function isGroup()
    {
        return $this->group;
    }

    public function jockeysOldDataFormat()
    {
        return $this->jockeys->pluck('id')->keyBy(function($item) {
            return $item;
        });
    }

    /*
        Attributes
    */
    // public function getLocationAttribute()
    // {
    //    return $this->location_name ?? $this->location->name;  
    // }

    public function getFormattedTypeAttribute()
    {
        return ucfirst($this->type->name);
    }

    public function getFormattedStartAttribute()
    {
        return $this->start->format('l jS F Y');
    }

    public function getFormattedStartDayMonthAttribute()
    {
        return $this->start->format('l jS F');
    }

    public function getStartDateAttribute()
    {
        return $this->start->format('d/m/Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start->format('H:i');
    }

    public function getFormattedStartTimeAmPmAttribute()
    {
        return $this->start->format('H:i a');
    }

    public function getFormattedLocationAttribute()
    {
        if($this->location) {
            return ucfirst($this->location->name);
        }

        return ucfirst($this->location_name);
    }

    public function getFormattedUpdatedOnAttribute()
    {
        return $this->updated_at->format('l jS F Y');
    }

    public function getFormattedCommentNameAttribute()
    {
        return "{$this->FormattedType} activity";
    }

    public function getFormattedJockeyOrGroupAttribute()
    {
        if($this->jockeys->count() === 1) {
            return $this->jockeys->first()->full_name;
        } else if($this->jockeys->count() > 1) {
            return 'Group';
        }
    }

    public function getInvoiceableGroupAttribute()
    {
        return 'activities';
    }

    public function getNotificationLinkAttribute()
    {
        // NOTE: need to have different urls depending on the users role.
        return config('app.url') . urlAppendByRole() . "/activity/{$this->id}";
    }  

    public function getEditRouteAttribute() {
        if($this->isGroup()) {
            return route(auth()->user()->roleName . '.group-activity.update', $this);
        }

        return route(auth()->user()->roleName . '.1:1-activity.update', $this);
    }
}
