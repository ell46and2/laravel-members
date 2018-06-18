<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Comment $comment)
    {
        // must be the author or an admin user
        return $user->id === $comment->author->id || $user->role->name === 'admin';
    }

    public function destroy(User $user, Comment $comment)
    {
        // must be the author or an admin user
        return $user->id === $comment->author->id || $user->role->name === 'admin';
    }
}
