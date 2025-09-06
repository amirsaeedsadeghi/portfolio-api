<?php

namespace App\Http\Filters;

/**
 * Class NavbarItemFilter
 *
 * Defines filtering and sorting logic for NavbarItem queries.
 *
 * Supported filters (via `filter[...]`):
 * - link   (LIKE, supports `*` wildcard)   Example: filter[link]=*about*
 * - label  (LIKE, supports `*` wildcard)   Example: filter[label]=Home*
 *
 * Supported sort fields (via `sort`):
 * - order, label
 *   Use comma-separated list; prefix with "-" for DESC.
 *   Examples:
 *     sort=order
 *     sort=-order,label
 *
 * Example usage:
 *   GET /api/v1/navbar-items?filter[label]=*contact*&sort=order
 *
 * @extends QueryFilter
 */
class NavbarItemFilter extends QueryFilter
{
    /**
     * Whitelisted sortable fields (API-visible name => DB column).
     *
     * @var array<string, string>
     */
    protected array $sortable = [
        'order' => 'order',
        'label' => 'label',
    ];

    /**
     * Fields that support LIKE queries (with `*` wildcard).
     *
     * @var string[]
     */
    protected array $likeFilters = ['link', 'label'];
}
