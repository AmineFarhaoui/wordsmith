<?php

namespace App\Library\Translations\Exports;

use Illuminate\Support\Collection;

class I18nextExport extends JsonExport
{
    /**
     * {@inheritdoc}
     */
    protected function getMappedData(): Collection
    {
        return parent::getMappedData()->undot();
    }
}
