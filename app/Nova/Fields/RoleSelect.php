<?php

namespace App\Nova\Fields;

use App\Library\Enumerations\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class RoleSelect extends Select
{
    /**
     * The field which should be used to map the role to.
     */
    protected string $roleKey = 'id';

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->resolveUsing(function (?Collection $roles, $resource) {
            return $this->getRole($resource);
        });

        $this->displayUsing(function (?Collection $roles, $resource) {
            return Role::getDescription($this->getRole($resource));
        });

        // Override the default fill callback set by the Enum field.
        $this->fillUsing(null);

        $this->options(Role::asSelectArray());
    }

    /**
     * {@inheritdoc}
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (! $request->exists($requestAttribute)) {
            return;
        }

        // Because this field force one role per model,
        // we're going to detach all other roles before attaching a new one.
        $model->roles()->detach();

        // If the role is given in the request, we'll assign it to the model. If
        // none where given the role is not change but detached instead.
        if (! is_null($request[$requestAttribute])) {
            $role = config('permission.models.role');

            $model->assignRole(
                $role::firstWhere($this->roleKey, $request[$requestAttribute]),
            );
        }
    }

    /**
     * Get the first role from the resource.
     */
    protected function getRole(Model $resource): string|int|null
    {
        return $resource->roles?->first()?->{$this->roleKey};
    }
}
