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
}
