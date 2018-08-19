<?php

namespace App\Policies;

use App\Models\Coach;
use App\Models\Jockey;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoachPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Coach $coach)
    {
        // Allow all except Jockeys that aren't assigned to the coach
        if($user->isJockey()) {
            $jockey = Jockey::find($user->id);
            if(!$jockey->isAssignedToCoach($coach->id)) {
                return false;
            }
        }

        return true;
    }
}
