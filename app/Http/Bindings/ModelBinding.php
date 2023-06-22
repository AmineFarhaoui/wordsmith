<?php

namespace App\Http\Bindings;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;

class ModelBinding
{
    /**
     * Binds routes that follow `/{model}/{id}` pattern.
     */
    public function bind($value, $route): Model
    {
        $model = str_replace('-', '_', $value);

        if (is_null($modelName = Relation::getMorphedModel($model))) {
            throw (new ModelNotFoundException)->setModel($model);
        }

        $id = $route->parameters()['id'] ?? null;

        $instance = (new Container())->make($modelName);

        return $instance->resolveRouteBinding($id);
    }
}
