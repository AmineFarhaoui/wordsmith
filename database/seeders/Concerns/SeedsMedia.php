<?php

namespace Database\Seeders\Concerns;

use Spatie\MediaLibrary\HasMedia;

trait SeedsMedia
{
    /**
     * Add a random cat image to the media model.
     */
    protected function seedMedia(HasMedia $model, string $collectionName): void
    {
        $name = mt_rand(0, 7);

        $model->addMedia(storage_path("app/seeder/images/$name.jpeg"))
            ->preservingOriginal()
            ->toMediaCollection($collectionName);
    }
}
