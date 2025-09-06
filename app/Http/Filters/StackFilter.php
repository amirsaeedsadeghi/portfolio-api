<?php

namespace App\Http\Filters;

/**
 * Class StackFilter
 *
 * Defines filtering and sorting logic for Stack queries.
 *
 * Supported filters (via `filter[...]`):
 * - name  (LIKE, supports `*` wildcard)   Example: filter[name]=*react*
 *
 * Supported sort fields (via `sort`):
 * - name, createdAt, updatedAt
 *   Use comma-separated list; prefix with "-" for DESC.
 *   Examples:
 *     sort=name
 *     sort=-createdAt,name
 *
 * Example usage:
 *   GET /api/v1/stacks?filter[name]=tail*&sort=-updatedAt
 *
 * @extends QueryFilter
 */
class StackFilter extends QueryFilter
{
    /**
     * Whitelisted sortable fields (API name => DB column).
     *
     * @var array<string, string>
     */
    protected array $sortable = [
        'name'      => 'name',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    /**
     * Fields that support LIKE matching (with `*` as wildcard).
     *
     * @var string[]
     */
    protected array $likeFilters = ['name'];
}
