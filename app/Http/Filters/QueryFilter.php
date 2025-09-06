<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Base abstract class for applying dynamic query filters via method mapping.
 *
 * Example usage:
 * User::filter(new UserFilter(['name' => 'ali', 'sort' => '-created_at']))->paginate();
 *
 * @method Builder filter(array $filter) Dynamically called to handle nested filter[] arrays.
 * @method Builder sort(string $value) Applies sorting based on whitelist.
 */
abstract class QueryFilter implements QueryFilterInterface
{
    /**
     * The current query builder instance.
     *
     * @var Builder
     */
    protected Builder $builder;

    /**
     * Input filters passed to the class.
     *
     * @var array
     */
    protected array $input;

    /**
     * A list of sortable columns or mappings like ['name' => 'users.name']
     *
     * @var array
     */
    protected array $sortable;

    /**
     * List of fields for LIKE search.
     *
     * @var string[]
     */
    protected array $likeFilters = [];

    /**
     * List of fields for exact match search (optional).
     *
     * @var string[]
     */
    protected array $exactFilters = [];

    /**
     * Create a new filter instance.
     *
     * @param array|null $input Optional array of inputs, defaults to request()->all()
     */
    public function __construct(?array $input = null)
    {
        $this->input = $input ?? request()->all();
    }

    /**
     * Apply all defined filters to the query builder.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->input as $name => $value) {
            if (method_exists($this, $name)) {
                $this->$name($value);
            }
        }

        return $this->builder;
    }

    /**
     * Apply sorting logic based on the 'sort' key.
     *
     * @param string $value Comma-separated field names, prefix with "-" for DESC.
     * @return Builder
     */
    public function sort(string $value): Builder
    {
        $sortedColumn = "";
        $sortArray = explode(',', $value);
        foreach ($sortArray as $item) {
            $direction = 'ASC';
            if (strpos($item, '-') === 0) {
                $direction = 'DESC';
                $item = substr($item, 1);
            }
            if (!in_array($item, $this->sortable) && !array_key_exists($item, $this->sortable)) {
                continue;
            }
            $sortedColumn = $this->sortable[$item] ?? $item;

            $this->builder->orderBy($sortedColumn, $direction);
        }

        return $this->builder;
    }

    /**
     * Handles filter[] syntax in query string by calling nested filter methods.
     *
     * @param array $filter
     * @return Builder
     *
     */
    protected function filter(array $filter): Builder
    {
        foreach ($filter as $name => $value) {
            if (method_exists($this, $name)) {
                $this->$name($value);
                continue;
            }

            if (in_array($name, $this->likeFilters, true)) {
                $this->likeQuery($name, $value);
                continue;
            }

            if (in_array($name, $this->exactFilters, true)) {
                $this->builder->where($name, $value);
                continue;
            }
        }
        return $this->builder;
    }

    /**
     * Filter records by created_at date or date range.
     *
     * Accepts a single date (e.g., `2024-01-01`) or a comma-separated date range
     * (e.g., `2024-01-01,2024-01-31`). Applies a `where` or `whereBetween`
     * condition to the `created_at` column accordingly.
     *
     * @param string $value A single date or a comma-separated date range.
     * @return Builder The modified query builder instance.
     */
    protected function createdAt(string $value): Builder
    {
        $dates = explode(',', $value);
        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }
        return $this->builder->where('created_at', '>=', $value);
    }

    /**
     * Filter records by updated_at date or date range.
     *
     * Accepts a single date (e.g., `2024-01-01`) or a comma-separated date range
     * (e.g., `2024-01-01,2024-01-31`). Applies a `where` or `whereBetween`
     * condition to the `updated_at` column accordingly.
     *
     * @param string $value A single date or a comma-separated date range.
     * @return Builder The modified query builder instance.
     */
    protected function updatedAt(string $value): Builder
    {
        $dates = explode(',', $value);
        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }
        return $this->builder->where('updated_at', '>=', $value);
    }

    /**
     * Apply a LIKE filter with support for * wildcard
     * 
     * @param string $column
     * @param string $value
     * 
     * @return Builder The modified query builder instance.
     */
    protected function likeQuery(string $column, string $value): Builder
    {
        $valueLike = str_replace('*', '%', $value);
        return $this->builder->where($column, 'like', $valueLike);
    }

    /**
     * Handle dynamic filter method calls.
     *
     * This method is triggered when a filter key does not have a dedicated
     * method in the filter class. It will:
     * - Apply a LIKE query if the key exists in $likeFilters
     * - Apply an exact match if the key exists in $exactFilters
     * - Otherwise, return the builder without modification
     *
     * Example:
     *   - filter[title]=*web*  → LIKE query on "title"
     *   - filter[status]=1     → Exact match on "status"
     *
     * @param string $method The filter key (called method name)
     * @param array  $arguments The filter value(s)
     * @return Builder The modified query builder instance
     */
    public function __call(string $method, array $arguments)
    {
        $value = $arguments[0] ?? null;

        if (in_array($method, $this->likeFilters, true)) {
            return $this->likeQuery($method, $value);
        }

        if (in_array($method, $this->exactFilters, true)) {
            return $this->builder->where($method, $value);
        }

        // Default behavior: just ignore unknown filters
        return $this->builder;
    }
}
