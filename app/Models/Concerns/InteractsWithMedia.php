<?php

namespace App\Models\Concerns;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia as BaseInteractsWithMedia;

trait InteractsWithMedia
{
    use BaseInteractsWithMedia;

    /**
     * Adds default media library conversations to the collection.
     */
    public function addDefaultMediaConversions(string $collectionName, array $extraConversations = []): void
    {
        $conversions = config('media-library.default_conversions', []) + $extraConversations;

        foreach ($conversions as $conversion => $size) {
            $this->addMediaConversion($conversion)
                ->crop(Manipulations::CROP_CENTER, $size, $size)
                ->performOnCollections($collectionName);
        }
    }
}
