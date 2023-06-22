<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilteredText implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $value
     */
    public function passes($attribute, $value): bool
    {
        if ($value === null) {
            return true;
        }

        // The attribute must only contain letters, numbers, dashes,
        // underscores, exclamation marks, dollar signs, commas, dots,
        // parenthesis, apostrophes, quotation marks and a space.
        return preg_match('/^[\pL\pM\pN !$,."\'()_-]+$/u', $value);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('validation.custom.filtered_text');
    }
}
