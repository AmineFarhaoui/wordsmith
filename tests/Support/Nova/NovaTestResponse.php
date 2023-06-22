<?php

namespace Tests\Support\Nova;

use Illuminate\Testing\TestResponse;

class NovaTestResponse extends TestResponse
{
    /**
     * The parsed content of the response.
     */
    protected ?array $data;

    /**
     * {@inheritdoc}
     */
    public function __construct($response)
    {
        parent::__construct($response);

        $this->data = json_decode($this->getContent(), true);
    }

    /**
     * Get a Nova field.
     */
    public function field(string $attribute): ?array
    {
        return collect(data_get($this->data, 'resource.fields'))
            ->firstWhere('attribute', $attribute);
    }
}
