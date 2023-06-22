<?php

namespace App\Library\Translations\Imports;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class I18nextImport extends JsonImport
{
    /**
     * {@inheritdoc}
     */
    protected function read(): array
    {
        $raw = Arr::dot(parent::read());

        $data = collect();

        foreach ($raw as $key => $value) {
            $isNestedKey = $this->isNewNestedKey($key);

            $key = $isNestedKey
                ? Str::beforeLast($key, '.')
                : $key;

            if (! $data->has($key)) {
                $value = $isNestedKey ? $this->getNestedValues($raw, $key) : $value;

                $data->put($key, $value);
            }
        }

        return $data->all();
    }

    /**
     * Determine if the given value is a nested key.
     */
    protected function isNewNestedKey(string $key): bool
    {
        return preg_match('/^\d+$/', last(explode('.', $key)));
    }

    /**
     * Get the nested values.
     */
    protected function getNestedValues(array $raw, string $parsedKey): array
    {
        $values = [];

        foreach ($raw as $key => $value) {
            if (Str::startsWith($key, $parsedKey.'.')) {
                $values[Str::after($key, $parsedKey.'.')] = $value;
            }
        }

        return $values;
    }
}
