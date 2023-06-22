<?php

namespace Database\Factories\Concerns;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

trait HasMedia
{
    /**
     * Add a media collection to the model.
     */
    public function withMedia(string $collectionName): Factory
    {
        return $this->afterCreating(function (Model $model) use ($collectionName) {
            Media::factory()
                ->forModel($model)
                ->collection($collectionName)
                ->create(['file_name' => 'some_image']);
        });
    }
}
