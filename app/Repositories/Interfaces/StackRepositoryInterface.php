<?php

namespace App\Repositories\Interfaces;

use App\Http\Filters\QueryFilterInterface;
use App\Models\Stack;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface StackRepositoryInterface
 *
 * Defines the contract for interacting with Stack entities in the data layer.
 * Provides methods for listing, paginating, finding, creating, updating, and deleting stacks.
 */
interface StackRepositoryInterface
{
    /**
     * Retrieve all stacks with optional filtering.
     *
     * @param QueryFilterInterface $filters Query filters to apply.
     * @return Collection<int, Stack> A collection of Stack models.
     */
    public function all(QueryFilterInterface $filters): Collection;

    /**
     * Find a stack by its primary key.
     *
     * @param string|int $id The primary key of the stack.
     * @return Stack The found Stack model.
     *
     * @throws ModelNotFoundException If no matching stack is found.
     */
    public function find(string|int $id): Stack;

    /**
     * Paginate stacks with optional filtering.
     *
     * @param QueryFilterInterface $filters Query filters to apply.
     * @param int $perPage Number of items per page. Defaults to 15.
     * @return LengthAwarePaginator Paginated list of Stack models.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new stack.
     *
     * @param array $data Key-value pairs of stack attributes.
     * @return Stack The newly created Stack model.
     */
    public function create(array $data): Stack;

    /**
     * Update an existing stack.
     *
     * @param Stack $model The Stack model to update.
     * @param array $data Key-value pairs of updated attributes.
     * @return Stack The updated Stack model.
     */
    public function updateModel(Stack $model, array $data): Stack;

    /**
     * Update an existing stack.
     *
     * @param string|int $id The Stack model to update.
     * @param array $data Key-value pairs of updated attributes.
     * @return Stack The updated Stack model.
     */
    public function updateById(string|int $id, array $data): Stack;

    /**
     * Delete a stack from storage.
     *
     * @param Stack $model The Stack model to delete.
     * @return void
     */
    public function deleteModel(Stack $model): void;

    /**
     * Delete a stack from storage.
     *
     * @param string|int $id The Stack model to delete.
     * @return void
     */
    public function deleteById(string|int $id): void;
}
