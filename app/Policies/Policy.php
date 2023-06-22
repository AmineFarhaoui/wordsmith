<?php

namespace App\Policies;

use App\Library\Enumerations\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

abstract class Policy
{
    use HandlesAuthorization;

    /**
     * Abilities which admin should not be auto-allowed to do.
     */
    protected array $disallowAdmin = [];

    /**
     * Check if the user has special privileges.
     *
     * @return bool|void
     */
    public function before(Model $model, string $ability)
    {
        if (in_array($ability, $this->disallowAdmin)) {
            return;
        }

        if ($model instanceof User
            && $model->hasRole(Role::ADMIN)) {
            return true;
        }
    }
}
