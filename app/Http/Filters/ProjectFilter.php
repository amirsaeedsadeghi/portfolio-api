<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProjectFilter
 *
 * Defines filtering, sorting, and eager-loading logic for Project queries.
 *
 * Supported filters (via `filter[...]`):
 * - title       (LIKE, supports `*` wildcard) Example: filter[title]=*portfolio*
 * - summary     (LIKE)                        Example: filter[summary]=*php*
 * - description (LIKE)                        Example: filter[description]=*framework*
 * - client      (LIKE)                        Example: filter[client]=avamafarin
 * - category    (LIKE)                        Example: filter[category]=web*
 * - slug        (LIKE)                        Example: filter[slug]=*ecommerce*
 * - order       (exact match)                 Example: filter[order]=1
 *
 * Supported sort fields (via `sort`):
 * - title, summary, description, client, category, order, slug,
 *   startDate, createdAt, updatedAt
 *   Use comma-separated list; prefix with "-" for DESC.
 *   Examples:
 *     sort=title
 *     sort=-createdAt,title
 *
 * Supported includes (via `includes` param):
 * - stacks, images
 *   Example: includes=stacks,images
 *
 * Example usage:
 *   GET /api/v1/projects?filter[client]=avamafarin&sort=-startDate&includes=stacks,images
 *
 * @extends QueryFilter
 */
class ProjectFilter extends QueryFilter
{
    /**
     * Whitelisted sortable fields (API-visible name => DB column).
     *
     * @var array<string, string>
     */
    protected array $sortable = [
        'title'       => 'title',
        'summary'     => 'summary',
        'description' => 'description',
        'client'      => 'client',
        'category'    => 'category',
        'order'       => 'order',
        'slug'        => 'slug',
        'startDate'   => 'start_date',
        'createdAt'   => 'created_at',
        'updatedAt'   => 'updated_at',
    ];

    /**
     * Fields that support LIKE queries (with `*` wildcard).
     *
     * @var string[]
     */
    protected array $likeFilters = [
        'title',
        'summary',
        'description',
        'client',
        'category',
        'slug',
    ];

    /**
     * Fields that must match exactly.
     *
     * @var string[]
     */
    protected array $exactFilters = ['order'];

    /**
     * Relations that can be eager-loaded with `includes`.
     *
     * @var string[]
     */
    protected array $relations = ['stacks', 'images'];

    /**
     * Apply "includes" parameter for eager-loading relationships.
     *
     * Accepts a comma-separated list of relations, only allowing
     * those whitelisted in {@see $relations}.
     *
     * Example: includes=stacks,images
     *
     * @param string $value Comma-separated relations.
     * @return Builder
     */
    public function includes(string $value): Builder
    {
        $relations = array_map(fn($relation) => strtolower(trim($relation)), explode(',', $value));
        $allowed   = array_intersect($relations, $this->relations);

        return $this->builder->with($allowed);
    }
}
