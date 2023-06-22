<?php

namespace App\Http\Resources;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'api_token' => $this->resource['api_token'],
            'user' => resource($this->resource['user']),
        ];
    }
}
