<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait ScopeDistinctColumns
{
    /**
     * The default distinct columns will use in the scope.
     */
    protected function getDefaultDistinctColumns(): array
    {
        return [];
    }

    /**
     * Scope a query to only unique fields.
     */
    public function scopeDistinctColumns(Builder $query, string ...$columns): void
    {
        $query->whereIn(
            'id',
            $query->clone()->selectRaw('max(id)')->groupBy($columns ?: $this->getDefaultDistinctColumns()),
        );
    }
}
