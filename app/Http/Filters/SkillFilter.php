<?php

namespace App\Http\Filters;

/**
 * Class SkillFilter
 *
 * Defines filtering and sorting logic for Skill queries.
 *
 * Supported filters (via `filter[...]`):
 * - title       (LIKE, supports `*` wildcard)   Example: filter[title]=*php*
 * - description (LIKE, supports `*` wildcard)   Example: filter[description]=*framework*
 *
 * Supported sort fields (via `sort`):
 * - title, description, createdAt, updatedAt
 *   Use comma-separated list; prefix with "-" for DESC.
 *   Examples:
 *     sort=title
 *     sort=-createdAt,title
 *
 * Example usage:
 *   GET /api/v1/skills?filter[title]=laravel*&sort=-updatedAt
 *
 * @extends QueryFilter
 */
class SkillFilter extends QueryFilter
{
    /**
     * Whitelisted sortable fields (API name => DB column).
     *
     * @var array<string, string>
     */
    protected array $sortable = [
        'title'       => 'title',
        'description' => 'description',
        'createdAt'   => 'created_at',
        'updatedAt'   => 'updated_at',
    ];

    /**
     * Fields that support LIKE matching (with `*` as wildcard).
     *
     * @var string[]
     */
    protected array $likeFilters = ['title', 'description'];
}
