<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function markAllNotificationsAsRead(User $user, User $requestUser)
    {
        return $user->id == $requestUser->id;
    }
}
