<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    /**
     * Create a new search filter instance.
     */
    public function __construct(private array $props)
    {
        //
    }

    /**
     * Filter models on null value or not.
     */
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function (Builder $query) use ($value) {
            foreach ($this->props as $prop) {
                $query->orWhere($prop, 'like', "%{$value}%");
            }
        });
    }
}
