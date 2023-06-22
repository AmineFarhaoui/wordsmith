<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy extends Policy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): bool
    {
        return $company->hasUser($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        return $company->hasUser($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        return $company->hasUser($user);
    }

    /**
     * Determine whether the user can view any users for the company.
     */
    public function viewAnyUser(User $user, Company $company): bool
    {
        return $company->hasUser($user);
    }
}