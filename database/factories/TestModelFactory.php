<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Support\Models\TestModel;

class TestModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TestModel::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
