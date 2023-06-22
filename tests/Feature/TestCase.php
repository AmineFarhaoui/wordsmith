<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Asserts a response.
     */
    protected function assertResponse(
        TestResponse $response,
        int $status = 200,
        bool $assertStructure = true,
        bool $assertContent = false,
    ): void {
        $response->assertStatus($status);

        if ($assertStructure) {
            $this->assertJsonStructureSnapshot($response);
        } elseif ($assertContent) {
            $this->assertMatchesJsonSnapshot($response);
        }
    }

    /**
     * Makes a show request.
     */
    protected function makeShowRequest(Model $target, User $user = null): TestResponse
    {
        $url = sprintf(
            '%s/%s',
            $this->routeNameFromModel($target),
            $target->id,
        );

        if ($user) {
            $this->actingAs($user);
        }

        return $this->json('GET', $url);
    }

    /**
     * Makes a index request.
     */
    protected function makeIndexRequest(
        Model $target,
        User $user = null,
        array $parameters = [],
    ): TestResponse {
        $url = sprintf(
            '%s?%s',
            $this->routeNameFromModel($target),
            Arr::query($this->addIdSort($parameters)),
        );

        if ($user) {
            $this->actingAs($user);
        }

        return $this->json('GET', $url);
    }

    /**
     * Makes a index relatable request.
     */
    protected function makeRelationRequest(
        Model $target,
        Model $relation,
        User $user = null,
        array $parameters = [],
    ): TestResponse {
        $url = sprintf(
            '%s/%s/%s?%s',
            $this->routeNameFromModel($target),
            $target->id,
            $this->routeNameFromModel($relation),
            Arr::query($this->addIdSort($parameters)),
        );

        if ($user) {
            $this->actingAs($user);
        }

        return $this->json('GET', $url);
    }

    /**
     * Return route name as kebab case from model.
     */
    protected function routeNameFromModel(Model $model): string
    {
        return Str::of($model->getMorphClass())
            ->camel()
            ->kebab()
            ->toString();
    }

    /**
     * Create the test response instance from the given response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Testing\TestResponse
     */
    protected function createTestResponse($response)
    {
        return \OwowAgency\LaravelTestResponse\TestResponse::fromBaseResponse($response);
    }

    /**
     * Sometimes the snapshot assertion fails because of an incorrect order. To
     * prevent this from happening we add the id sort in the HTTP queries via
     * this method.
     */
    protected function addIdSort(array $data): array
    {
        if (! array_key_exists('sort', $data)) {
            $data['sort'] = '';
        }

        if (! str_contains($data['sort'], 'id')) {
            $data['sort'] .= ',id';
        }

        return $data;
    }

    /**
     * Overwrite of call() in Illuminate\Foundation\Testing\TestCase.
     * The payload needs to be converted to camelCase, before the HTTP request is made.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  null|string  $content
     * @return \Illuminate\Testing\TestResponse
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        // Convert the $parameters into camelCase.
        // The $parameters contains the payload for post(), put(), patch(), delete(),
        // and option() functions.
        if (! empty($parameters)) {
            $parameters = array_keys_convert_case(
                $parameters,
                'camel',
            );
        }

        // Convert the $content into camelCase.
        // The $content contains the payload for json() function. It is already encoded,
        // so it needs to be decoded, converted to camelCase, and then encoded again.
        if (! is_null($content)) {
            $content = array_keys_convert_case(
                json_decode($content, true) ?? [],
                'camel',
            );

            $content = json_encode($content);
        }

        // Camel case conversion is done, invoke the doc's call. Usually the
        // parent's call needs to be invoked but since the DocsGenerator has a
        // call method we need to invoke that one.
        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }
}
