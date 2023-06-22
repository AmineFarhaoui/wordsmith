<?php

namespace Database\Factories;

use Database\Factories\Concerns\HasModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    use HasModel;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'model_id' => rand(999, 99999),
            'uuid' => $this->faker->uuid(),
            'model_type' => $this->faker->name(),
            'collection_name' => $this->faker->name(),
            'name' => $this->faker->name(),
            'file_name' => $this->faker->name(),
            'mime_type' => $this->faker->mimeType(),
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => rand(999, 99999),
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
            'order_column' => null,
        ];
    }

    /**
     * Set the mime type of the media model to an image.
     */
    public function image(string $mimeType = 'image/jpeg'): MediaFactory
    {
        return $this->state(fn (array $attributes) => [
            'mime_type' => $mimeType,
        ]);
    }

    /**
     * Set the collection name of the media model.
     */
    public function collection(string $collectionName): MediaFactory
    {
        return $this->state(fn (array $attributes) => [
            'collection_name' => $collectionName,
        ]);
    }
}
