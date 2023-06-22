<?php

namespace Database\Factories\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

trait HasModel
{
    /**
     * Set the morphed model for the model.
     */
    public function forModel(Model $model): Factory
    {
        return $this->state(fn (array $attributes) => [
            'model_id' => $model->id,
            'model_type' => $model->getMorphClass(),
        ]);
    }
}
