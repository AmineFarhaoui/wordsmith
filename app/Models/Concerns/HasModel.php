<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphTo;

trait HasModel
{
    /**
     * Related model.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
