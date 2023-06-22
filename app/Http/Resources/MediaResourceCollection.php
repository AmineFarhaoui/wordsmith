<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaResourceCollection extends ResourceCollection
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
    public function __construct($resource, bool $forceMediaResource = false)
    {
        parent::__construct($resource);

        $this->forceMediaResource = $forceMediaResource;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(function (MediaResource $resource) {
            $resource->forceMediaResource = $this->forceMediaResource;

            return $resource;
        })->toArray();
    }
}
