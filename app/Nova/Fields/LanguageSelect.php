<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Select;

class LanguageSelect extends Select
{
    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  null|string  $attribute
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->options($this->getLanguages())
            ->displayUsingLabels();
    }

    /**
     * Get the languages.
     */
    protected function getLanguages(): array
    {
        return __('general.languages');
    }
}
