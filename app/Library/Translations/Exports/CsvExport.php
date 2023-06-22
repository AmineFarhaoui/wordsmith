<?php

namespace App\Library\Translations\Exports;

class CsvExport extends ExcelExport
{
    /**
     * Get the file extension for this export.
     */
    public function getFileExtension(): string
    {
        return 'csv';
    }
}
