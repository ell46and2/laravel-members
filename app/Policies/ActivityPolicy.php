<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function addComment(User $user, Activity $activity)
    {
        // must be the coach for the activity OR a jockey who belongs to the activity
        return $user->id == $activity->coach_id || $activity->jockeys->pluck('id')->contains($user->id);
    }

    public function delete(User $user, Activity $activity)
    {
    	return $user->id == $activity->coach_id || $user->role->name === 'admin';
    }

    public function update(User $user, Activity $activity)
    {
        return $user->id == $activity->coach_id || $user->role->name === 'admin';
    }

    public function edit(User $user, Activity $activity)
    {
        // if no invoice line
        $invoiceLine = $activity->invoiceLine;
        if(!$invoiceLine) {
           return $user->id == $activity->coach_id || $user->role->name === 'admin'; 
        }
        
        $invoice = $invoiceLine->invoice;
        if($invoice->status == 'approved') {
            return false;
        }

        return $user->role->name === 'admin';
    }

    public function show(User $user, Activity $activity)
    {
        // only allow jockeys that are assigned to the activity
        if($user->isJockey()) {
            return $activity->jockeys->pluck('id')->contains($user->id);
        } else {
            return true;
        }
    }
}
