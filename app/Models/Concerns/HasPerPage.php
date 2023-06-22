<?php

namespace App\Models\Concerns;

trait HasPerPage
{
    /**
     * Get the number of models to return per page.
     */
    public function getPerPage(): int
    {
        return (int) request('per_page', $this->perPage);
    }
}
