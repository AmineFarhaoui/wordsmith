<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TranslationExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * TranslationExport constructor.
     *
     * @param  \Illuminate\Support\Collection<int, \App\Models\Translation>  $models
     */
    public function __construct(protected Collection $models)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function collection(): Collection
    {
        return $this->models;
    }

    /**
     * {@inheritdoc}
     */
    public function headings(): array
    {
        return [
            'key',
            'value',
            'is_nested',
            'tags',
            'description',
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @param  \App\Models\Translation  $row
     */
    public function map($row): array
    {
        $value = $row->translationValues->first();

        return [
            $row->key,
            $value?->value,
            $row->is_nested,
            $row->tags?->isNotEmpty() ? $row->tags->implode('name', ',') : null,
            $row->description,
        ];
    }
}
