<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TranslationValue>
 */
class TranslationValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'translation_id' => Translation::factory(),
            'language' => $this->faker->randomElement(['en', 'nl']),
            'value' => $this->faker->sentence,
            'verified_at' => $this->faker->optional()->dateTimeBetween('-1 year'),
        ];
    }
}
