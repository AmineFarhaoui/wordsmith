<?php

namespace Tests\Unit\Library\Translations\Exports;

use App\Library\Translations\Exports\Export;
use App\Library\Translations\Exports\JsonExport;
use Illuminate\Support\Collection;

class JsonExportTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function export(Collection $models): Export
    {
        return new JsonExport('en', $models);
    }
}
