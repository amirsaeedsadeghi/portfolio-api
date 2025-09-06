<?php

namespace App\Http\Filters;

/**
 * Class UserFilter
 *
 * Applies filtering/sorting rules for User queries based on request input.
 *
 * Supported filters (via `filter[...]`):
 * - name  (LIKE, supports `*` wildcard)   Example: filter[name]=*john*
 * - email (LIKE, supports `*` wildcard)   Example: filter[email]=*@example.com
 *
 * Supported sort fields (via `sort`):
 * - name, email, createdAt, updatedAt
 *   Use comma-separated list; prefix with "-" for DESC.
 *   Examples:
 *     sort=createdAt
 *     sort=-createdAt,name
 *
 * Example usage:
 *   GET /api/v1/users?filter[name]=john*&filter[email]=*@example.com&sort=-createdAt
 *
 * @extends QueryFilter
 */
class UserFilter extends QueryFilter
{
    /**
     * Whitelisted sortable fields (API name => DB column).
     *
     * @var array<string, string>
     */
    protected array $sortable = [
        'name'      => 'name',
        'email'     => 'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    /**
     * Fields that use LIKE matching (with `*` as wildcard).
     *
     * @var string[]
     */
    protected array $likeFilters = ['name', 'email'];
}
