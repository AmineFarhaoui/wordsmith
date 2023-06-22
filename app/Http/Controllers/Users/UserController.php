<?php

namespace App\Http\Controllers\Users;

use App\Library\Services\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OwowAgency\LaravelResources\Controllers\ResourceController;

class UserController extends ResourceController
{
    /**
     * {@inheritdoc}
     */
    public function __construct(private UserService $userService)
    {
        parent::__construct();
    }

    /**
     * Returns the model instance for the show action.
     */
    public function showModel(Model $model): Model
    {
        return $model->load($this->defaultRelations());
    }

    /**
     * Updates and returns the model instance for the update action.
     */
    public function updateModel(Request $request, Model $model): void
    {
        $this->userService
            ->update($model, $request->validated())
            ->load($this->defaultRelations());
    }

    /**
     * The default relations which we need to eager load for the user.
     */
    private function defaultRelations(): array
    {
        return [
            'media',
        ];
    }
}
