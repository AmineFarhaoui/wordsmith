<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends Policy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $user, User $target): bool
    {
        return $user->is($target)
            || $target->companies()
                ->whereRelation('users', 'users.id', $user->id)
                ->exists();
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }

    /**
     * Determine whether the user can delete the user.
     */
    public function delete(User $user, User $target): bool
    {
        return $user->id === $target->id;
    }
}
