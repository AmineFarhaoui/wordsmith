<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DistinctArray implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $value
     */
    public function passes($attribute, $value): bool
    {
        return count($value) === count(array_unique($value));
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('validation.distinct');
    }
}
