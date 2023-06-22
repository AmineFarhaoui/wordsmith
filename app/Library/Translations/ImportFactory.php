<?php

namespace App\Library\Translations;

use App\Library\Translations\Imports\Import;
use Illuminate\Http\UploadedFile;

class ImportFactory
{
    /**
     * Create a new import instance.
     */
    public static function create(
        string $fileType,
        UploadedFile $file,
    ): Import {
        $class = match ($fileType) {
            'csv' => Exports\CsvExport::class,
            'excel' => Imports\ExcelImport::class,
            'json' => Imports\JsonImport::class,
            'i18next' => Imports\I18nextImport::class,
            default => throw new \Exception("Invalid file type ('$fileType')."),
        };

        return new $class($file);
    }
}
