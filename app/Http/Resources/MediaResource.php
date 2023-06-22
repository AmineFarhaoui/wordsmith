<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class MediaResource extends JsonResource
{
    /**
     * Force the resource to use its own array strucutre instead of possible
     * exceptions.
     *
     * @var bool
     */
    public $forceMediaResource = false;

    /**
     * Create a new resource instance.
     */
    public function __construct(Media $resource, bool $forceMediaResource = false)
    {
        parent::__construct($resource);

        $this->forceMediaResource = $forceMediaResource;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        if (! $this->forceMediaResource && $this->isImage()) {
            return (new MediaImageResource($this->resource))->toArray($request);
        }

        return [
            'id' => $this->resource->id,
            'file_name' => $this->resource->file_name,
            'size' => $this->resource->size,
            'url' => $this->getUrl(),
        ];
    }

    /**
     * Determines if the resource is an image.
     */
    public function isImage(): bool
    {
        return Str::contains($this->resource->mime_type, 'image');
    }

    /**
     * Returns the url. The method of retrieving it varies on the disk it is
     * stored on.
     */
    public function getUrl(string $conversion = ''): string
    {
        switch ($this->resource->disk) {
            case 's3':
                $expiration = now()->addMinutes(
                    config('filesystems.disks.s3.sign_expiration', 3600),
                );

                return $this->resource->getTemporaryUrl($expiration, $conversion);
                break;

            default:
                return $this->resource->getFullUrl($conversion);
                break;
        }
    }

    /**
     * Create new anonymous resource collection.
     *
     * @return \App\Http\Resources\MediaResourceCollection
     */
    public static function collection($resource, bool $forceMediaResource = false): MediaResourceCollection
    {
        return new MediaResourceCollection($resource, $forceMediaResource);
    }
}
