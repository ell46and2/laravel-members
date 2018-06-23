<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function dismiss(User $user, Notification $notification)
    {
        return $user->id == $notification->user_id;
    }
}
