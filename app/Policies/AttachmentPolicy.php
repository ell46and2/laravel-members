<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttachmentPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Attachment $attachment)
    {
        return $user->role->name === 'admin' || $user->role->name === 'coach';
    }
}
