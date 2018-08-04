<?php

namespace App\Policies;

use App\Models\Jockey;
use App\Models\SkillProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SkillProfilePolicy
{
    use HandlesAuthorization;

    public function edit(User $user, SkillProfile $skillProfile)
    {
        // Coach who created or Admin
        return $user->id === $skillProfile->coach_id || $user->isAdmin();
    }

    public function show(User $user, SkillProfile $skillProfile)
    {
        // Jockey who's skill profile it is, Assigned Coach or Admin
        $jockey = Jockey::find($skillProfile->jockey_id); 

        return $user->id === $jockey->id
            || $jockey->isAssignedToCoach($user->id)
            || $user->isAdmin();
    }
  
}
