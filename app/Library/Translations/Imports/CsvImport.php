<?php

namespace App\Library\Translations\Imports;

class CsvImport extends ExcelImport
{
    /**
     * Get the reader type.
     */
    protected function readerType(): string
    {
        return \Maatwebsite\Excel\Excel::CSV;
    }
}
