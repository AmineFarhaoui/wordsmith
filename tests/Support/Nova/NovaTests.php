<?php

namespace Tests\Support\Nova;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

trait NovaTests
{
    /**
     * Visit the given resource with a GET request.
     */
    protected function novaGet(string|Model $resource): TestResponse
    {
        return $this->get($this->buildNovaApiUrl($resource));
    }

    /**
     * Visit the given resource with a POST request.
     */
    protected function novaPost(string $resource, array $data = [], array $headers = []): TestResponse
    {
        return $this->post($this->buildNovaApiUrl($resource), $data, $headers);
    }

    /**
     * Visit the given resource with a PATCH request.
     */
    protected function novaPut(Model $resource, array $data = [], array $headers = []): TestResponse
    {
        return $this->put($this->buildNovaApiUrl($resource), $data, $headers);
    }

    /**
     * Build the Nova API url.
     */
    protected function buildNovaApiUrl(string|Model $model): string
    {
        $resource = Str::slug(is_string($model) ? $model : $model->getMorphClass());

        $url = "nova-api/$resource";

        // If the resource is a model, we can assume we want to show that
        // specific model.
        if ($model instanceof Model) {
            $url .= "/$model->id";
        }

        return $url;
    }

    /**
     * Create the test response instance from the given response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Testing\TestResponse
     */
    protected function createTestResponse($response)
    {
        return NovaTestResponse::fromBaseResponse($response);
    }
}
