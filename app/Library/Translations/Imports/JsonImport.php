<?php

namespace App\Library\Translations\Imports;

use App\Library\Translations\RawTranslation;

class JsonImport extends Import
{
    /**
     * {@inheritdoc}
     */
    protected function read(): array
    {
        return json_decode($this->file->getContent(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function map(mixed $key, $value): RawTranslation
    {
        return RawTranslation::make()
            ->setKey($key)
            ->setValue($value)
            ->setIsNested(is_array($value));
    }
}
