<?php

namespace App\Library\Translations\Imports;

use App\Imports\TranslationImport;
use App\Library\Translations\RawTranslation;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImport extends Import
{
    /**
     * {@inheritdoc}
     */
    protected function read(): array
    {
        $data = Excel::toArray(
            new TranslationImport(),
            $this->file,
            readerType: $this->readerType(),
        );

        return head($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function map(mixed $key, mixed $value): RawTranslation
    {
        return RawTranslation::make()
            ->setKey($value['key'])
            ->setValue($value['value'])
            ->setDescription($value['description'])
            ->setTags(array_filter(explode(',', $row['tags'] ?? '')))
            ->setIsNested(! is_string($value));
    }

    /**
     * Get the reader type.
     */
    protected function readerType(): string
    {
        return \Maatwebsite\Excel\Excel::XLSX;
    }
}
