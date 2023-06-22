<?php

namespace Tests\Unit\Library\Translations\Exports;

use App\Library\Translations\Exports\Export;
use App\Library\Translations\Exports\I18nextExport;
use Illuminate\Support\Collection;

class I18nextExportTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function export(Collection $models): Export
    {
        return new I18nextExport('en', $models);
    }
}
