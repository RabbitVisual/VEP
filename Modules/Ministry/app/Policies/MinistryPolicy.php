<?php

namespace VertexSolutions\Ministry\Policies;

use App\Models\User;
use VertexSolutions\Ministry\Models\Ministry;

class MinistryPolicy
{
    /**
     * Determine whether the user can view the ministry.
     */
    public function view(User $user, Ministry $ministry): bool
    {
        return $ministry->members()->where('user_id', $user->id)->exists()
            || $ministry->leader_id === $user->id;
    }

    /**
     * Determine whether the user can update the ministry (leader or collaborator).
     */
    public function update(User $user, Ministry $ministry): bool
    {
        return $ministry->isCollaboratorOrLeader($user);
    }

    /**
     * Determine whether the user can delete the ministry (leader only).
     */
    public function delete(User $user, Ministry $ministry): bool
    {
        return $ministry->isLeader($user);
    }
}
