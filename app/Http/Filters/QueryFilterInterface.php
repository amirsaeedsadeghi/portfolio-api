<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface QueryFilterInterface
 *
 * Represents a filter that can be applied to an Eloquent query builder.
 * All filter classes implementing this interface should define the logic
 * for applying filtering rules to a query.
 */
interface QueryFilterInterface
{
    /**
     * Apply filter conditions to the given Eloquent query builder.
     *
     * @param Builder $builder The base query to which filters will be applied.
     * @return Builder The modified query builder with filters applied.
     */
    public function apply(Builder $builder): Builder;
}