<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProjectImageFilter
 *
 * Defines filtering and sorting logic for ProjectImage queries.
 *
 * Supported filters (via `filter[...]`):
 * - order (exact match)   Example: filter[order]=1
 *
 * Supported sort fields (via `sort`):
 * - order, createdAt, updatedAt
 *   Use comma-separated list; prefix with "-" for DESC.
 *   Examples:
 *     sort=order
 *     sort=-createdAt,order
 *
 * Supported includes (via `includes` param):
 * - project
 *   Example: includes=project
 *
 * Example usage:
 *   GET /api/v1/project-images?filter[order]=1&sort=-updatedAt&includes=project
 *
 * @extends QueryFilter
 */
class ProjectImageFilter extends QueryFilter
{
    /**
     * Fields that require exact matches.
     *
     * @var string[]
     */
    protected array $exactFilters = [
        'order',
    ];

    /**
     * Whitelisted sortable fields (API name => DB column).
     *
     * @var array<string, string>
     */
    protected array $sortable = [
        'order'     => 'order',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    /**
     * Relations that can be eagerly loaded.
     *
     * @var string[]
     */
    protected array $relations = ['project'];

    /**
     * Apply "includes" parameter for eager-loading relationships.
     *
     * Accepts a comma-separated list of relations and only loads
     * those present in {@see $relations}.
     *
     * Example: includes=project
     *
     * @param string $value Comma-separated list of relations.
     * @return Builder
     */
    public function includes(string $value): Builder
    {
        $relations = array_map(fn($relation) => strtolower(trim($relation)), explode(',', $value));
        $allowed   = array_intersect($relations, $this->relations);

        return $this->builder->with($allowed);
    }
}
