<?php

namespace App\Policies;

use App\Models\RacingExcellence;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RacingExcellencePolicy
{
    use HandlesAuthorization;

    public function addParticipantResults(User $user, RacingExcellence $racingExcellence)
    {
        // must be the assigned coach or an admin user
        return $user->id == $racingExcellence->coach_id || $user->role->name === 'admin';
    }

    public function coachEdit(User $user, RacingExcellence $racingExcellence)
    {
    	return $user->id == $racingExcellence->coach_id;
    }
}
