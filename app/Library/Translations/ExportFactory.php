<?php

namespace App\Library\Translations;

use App\Library\Translations\Exports\Export;
use Illuminate\Support\Collection;

class ExportFactory
{
    /**
     * Create a new export instance.
     */
    public static function create(
        string $fileType,
        string $language,
        Collection $models,
    ): Export {
        $class = match ($fileType) {
            'csv' => Exports\CsvExport::class,
            'excel' => Exports\ExcelExport::class,
            'json' => Exports\JsonExport::class,
            'i18next' => Exports\I18nextExport::class,
            default => throw new \Exception("Invalid file type ('$fileType')."),
        };

        return new $class($language, $models);
    }
}
