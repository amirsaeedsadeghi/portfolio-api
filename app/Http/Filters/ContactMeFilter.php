<?php

namespace App\Http\Filters;

/**
 * Class ContactMeFilter
 *
 * Defines filtering and sorting logic for ContactMe queries.
 *
 * Supported filters (via `filter[...]`):
 * - name        (LIKE, supports `*` wildcard) Example: filter[name]=*roham*
 * - email       (LIKE)                        Example: filter[email]=*@gmail.com
 * - messageBody (LIKE)                        Example: filter[messageBody]=*testing*
 *
 * Supported sort fields (via `sort`):
 * - name, createdAt, updatedAt
 *   Use comma-separated list; prefix with "-" for DESC.
 *   Examples:
 *     sort=name
 *     sort=-createdAt,name
 *
 * Example usage:
 *   GET /api/v1/contact-me?filter[email]=*@gmail.com&sort=-createdAt
 *
 * @extends QueryFilter
 */
class ContactMeFilter extends QueryFilter
{
    /**
     * Whitelisted sortable fields (API-visible name => DB column).
     *
     * @var array<string, string>
     */
    protected array $sortable = [
        'name'      => 'name',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    /**
     * Fields that support LIKE queries (with `*` wildcard).
     *
     * @var array<string, string>
     */
    protected array $likeFilters = [
        'name'        => 'name',
        'email'       => 'email',
        'messageBody' => 'message_body',
    ];
}
