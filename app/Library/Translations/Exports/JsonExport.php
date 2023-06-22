<?php

namespace App\Library\Translations\Exports;

use App\Models\Translation;
use Illuminate\Support\Collection;

class JsonExport extends Export
{
    /**
     * Get the contents for this export.
     */
    public function getContents(): string
    {
        return json_encode($this->getMappedData(), JSON_PRETTY_PRINT);
    }

    /**
     * Get the mapped data for this export.
     */
    protected function getMappedData(): Collection
    {
        return $this->models
            ->mapWithKeys(fn (Translation $translation) => [
                $translation->key => $this->parseValue($translation),
            ])
            ->sortKeys();
    }

    /**
     * Get the file extension for this export.
     */
    public function getFileExtension(): string
    {
        return 'json';
    }

    /**
     * Parse the value.
     */
    protected function parseValue(Translation $translation): string|array|null
    {
        $value = $translation->translationValues->first()?->value;

        if ($translation->is_nested) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
