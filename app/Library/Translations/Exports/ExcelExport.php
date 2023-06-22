<?php

namespace App\Library\Translations\Exports;

use App\Exports\TranslationExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExport extends Export
{
    /**
     * Get the contents for this export.
     */
    public function getContents(): string
    {
        $writerType = match ($this->getFileExtension()) {
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
            'xls' => \Maatwebsite\Excel\Excel::XLS,
            default => throw new \Exception("Invalid file extension ('{$this->getFileExtension()}')."),
        };

        return Excel::raw(new TranslationExport($this->models), $writerType);
    }

    /**
     * Get the file extension for this export.
     */
    public function getFileExtension(): string
    {
        return 'xlsx';
    }
}
