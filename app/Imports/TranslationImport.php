<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TranslationImport implements SkipsEmptyRows, WithHeadingRow, WithValidation
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            'key' => 'required|string',
            'value' => 'nullable|string',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
        ];
    }
}
