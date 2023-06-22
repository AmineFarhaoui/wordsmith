<?php

namespace App\Library\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class RelationService
{
    /*
     * Sync and detach the given relations of the model. This will attach new
     * relations, sync the pivot data of attached relations and detaches old
     * relations.
     */
    public function syncAndDetachRelations(Model $model, string $relation, array $relationData): Model
    {
        if (count($toSync = data_get($relationData, 'sync', [])) > 0) {
            $this->syncRelations($model, $relation, $toSync);
        }

        if (count($toDetach = data_get($relationData, 'detach', [])) > 0) {
            $this->detachRelations($model, $relation, $toDetach);
        }

        return $model;
    }

    /**
     * Sync the given relations from the model together with the pivot data. If
     * the relations are already attached it will also sync the pivot data.
     */
    public function syncRelations(Model $model, string $relation, array $relationData): Model
    {
        $model->$relation()->syncWithoutDetaching(
            collect($relationData)->mapWithKeys(fn ($data) => [
                Arr::pull($data, 'id') => $data,
            ]),
        );

        return $model;
    }

    /**
     * Detach the given relation ids from the model.
     */
    public function detachRelations(Model $model, string $relation, array $relationIds): Model
    {
        $model->$relation()->detach($relationIds);

        return $model;
    }
}
