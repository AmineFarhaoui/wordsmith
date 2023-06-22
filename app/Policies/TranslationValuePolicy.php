<?php

namespace App\Policies;

use App\Models\TranslationValue;
use App\Models\User;

class TranslationValuePolicy extends Policy
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
    public function view(User $user, TranslationValue $translationValue): bool
    {
        return $translationValue->translation()
            ->whereRelation('project.companies.users', 'users.id', $user->id)
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
    public function update(User $user, TranslationValue $translationValue): bool
    {
        return $translationValue->translation()
            ->whereRelation('project.companies.users', 'users.id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TranslationValue $translationValue): bool
    {
        return $translationValue->translation()
            ->whereRelation('project.companies.users', 'users.id', $user->id)
            ->exists();
    }
}
