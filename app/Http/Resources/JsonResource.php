<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;
use Illuminate\Http\Resources\MissingValue;

abstract class JsonResource extends BaseJsonResource
{
    /**
     * Get the media resource for the given collection.
     */
    public function getMedia(string $collectionName): mixed
    {
        if (! $this->relationLoaded('media')) {
            return new MissingValue();
        }

        $collection = $this->getMediaCollection($collectionName);

        throw_if(
            $collection === null,
            new \Exception("Collection [$collectionName] doesn't exist."),
        );

        throw_if(
            ! $collection->singleFile,
            new \Exception("Collection [$collectionName] isn't a single file collection."),
        );

        return $this->getMediaImageResource($this->getFirstMedia($collectionName), $collectionName);
    }

    /**
     * Get the media image resource.
     */
    protected function getMediaImageResource(
        ?Media $media,
        string $collectionName,
    ): ?MediaImageResource {
        if ($media === null) {
            return null;
        }

        return (new MediaImageResource($media))
            ->conversionsFor($collectionName);
    }
}
