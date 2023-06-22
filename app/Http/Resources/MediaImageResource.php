<?php

namespace App\Http\Resources;

class MediaImageResource extends MediaResource
{
    /**
     * The collection for which we need to get the conversions for.
     */
    protected ?string $conversionsFor = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $data = [
            'original' => $this->getUrl(),
        ];

        $this->addConversions($data);

        return $data;
    }

    /**
     * Adds the conversions to the data that is being returned as array.
     */
    public function addConversions(array &$data): void
    {
        foreach ($this->resource->getMediaConversionNames() as $name) {
            $data[$name] = $this->getUrl($name);
        }
    }

    /*
     * Set for which collection we want to grab the conversions for.
     */
    public function conversionsFor(string $collection): static
    {
        $this->conversionsFor = $collection;

        return $this;
    }
}
