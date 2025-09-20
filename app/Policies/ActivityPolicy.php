<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActivityPolicy
{

    private function isSuperAdmin(User $user): bool
    {
        return $user->role->name === 'SuperAdmin' || $user->role->id === 1;
    }

    private function isAdmin(User $user): bool
    {
        return $user->role->name === 'Admin' || $user->role->id === 2;
    }

    private function isPj(User $user): bool
    {
        return $user->role->name === 'PJ' || $user->role->id === 3;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($this->isSuperAdmin($user) || $this->isAdmin($user)) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity): bool
    {
        if ($this->isSuperAdmin($user) || $this->isAdmin($user)) {
            return true;
        }

        // pj hanya boleh edit miliknya
        if ($this->isPj($user)) {
            return $activity->user_id == $user->id;
        }

        return false;
    }

    /**
     * Determine whether the only admin can update the model.
     */
    public function adminUpdate(User $user, Activity $activity): bool
    {
        if ($this->isSuperAdmin($user) || $this->isAdmin($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $activity): bool
    {
        if ($this->isSuperAdmin($user) || $this->isAdmin($user)) {
            return true;
        }

        return false;
    }
}
