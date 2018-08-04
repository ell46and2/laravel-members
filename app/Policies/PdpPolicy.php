<?php

namespace App\Policies;

use App\Models\Pdp;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PdpPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Pdp $pdp)
    {
        return $user->id === $pdp->jockey_id || $user->isAdmin() || $user->isJet();
    }

    public function complete(User $user, Pdp $pdp)
    {
        return $user->isAdmin() || $user->isJet();
    }
}
