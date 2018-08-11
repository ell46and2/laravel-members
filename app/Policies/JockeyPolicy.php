<?php

namespace App\Policies;

use App\Models\Jockey;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JockeyPolicy
{
    use HandlesAuthorization;

    public function profile(User $user, Jockey $jockey)
    {

        $userRole = $user->role_name;

        if($userRole === 'admin') return true;

        if(!$user->approved) return false; // if jockey unapproved, only allow Admin access
        
        // else allow Admin, Jets and Coach, and actual jockey access

        if($userRole === 'jets' || $userRole === 'coach') return true;

        if($user->id === $jockey->id) return true;

        return false;
    }
}
