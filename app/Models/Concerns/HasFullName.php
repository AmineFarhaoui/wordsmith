<?php

namespace App\Models\Concerns;

trait HasFullName
{
    /**
     * Boot the event has full name trait.
     */
    public static function bootHasFullName(): void
    {
        static::saving(function (self $model) {
            $parts = array_filter([trim($model->first_name), trim($model->last_name)]);

            $model->full_name = count($parts) > 0
                ? implode(' ', $parts)
                : null;
        });
    }
}
