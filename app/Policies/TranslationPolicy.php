<?php

namespace App\Policies;

use App\Models\Translation;
use App\Models\User;

class TranslationPolicy extends Policy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->companies()->count() > 0;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Translation $translation): bool
    {
        return $translation->project()
            ->whereRelation('companies.users', 'users.id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->companies()->count() > 0;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Translation $translation): bool
    {
        return $translation->project()
            ->whereRelation('companies.users', 'users.id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Translation $translation): bool
    {
        return $translation->project()
            ->whereRelation('companies.users', 'users.id', $user->id)
            ->exists();
    }
}
