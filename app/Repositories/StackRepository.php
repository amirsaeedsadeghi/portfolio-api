<?php

namespace App\Repositories;

use App\Http\Filters\QueryFilterInterface;
use App\Models\Stack;
use App\Repositories\Interfaces\StackRepositoryInterface;
use App\Traits\NormalizesData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class StackRepository
 *
 * Repository for managing Stack entities.
 *
 * Responsibilities:
 * - Fetching all stacks with or without pagination.
 * - Creating, updating, and deleting stacks.
 * - Applying query filters dynamically.
 */
class StackRepository implements StackRepositoryInterface
{
    use NormalizesData;

    /**
     * Retrieve all stacks matching the given filter.
     *
     * @param QueryFilterInterface $filters Filters to apply.
     * @return Collection<int, Stack> A collection of stacks.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return Stack::filter($filters)->get();
    }

    /**
     * Find a stack by its ID.
     *
     * @param string|int $id The stack ID.
     * @return Stack The found stack.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no stack is found.
     */
    public function find(string|int $id): Stack
    {
        return Stack::findOrFail($id);
    }

    /**
     * Paginate the list of stacks matching the given filter.
     *
     * @param QueryFilterInterface $filters Filters to apply.
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator Paginated list of stacks.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Stack::filter($filters)->paginate($perPage);
    }

    /**
     * Create a new stack record.
     *
     * @param array $data The stack data.
     * @return Stack The newly created stack.
     */
    public function create(array $data): Stack
    {
        return Stack::create($this->normalize($data));
    }

    /**
     * Update an existing stack with the provided data.
     *
     * @param Stack $stack The stack to update.
     * @param array $data The new data to apply.
     * @return Stack The updated and refreshed stack.
     */
    public function updateModel(Stack $stack, array $data): Stack
    {
        $stack->fill($this->normalize($data))->save();
        return $stack->refresh();
    }

    /**
     * Update an existing stack with the provided data.
     *
     * @param string|int $id The stack to update.
     * @param array $data The new data to apply.
     * @return Stack The updated and refreshed stack.
     */
    public function updateById(string|int $id, array $data): Stack
    {
        $stack = $this->find($id);
        $stack->fill($this->normalize($data))->save();
        return $stack->refresh();
    }

    /**
     * Delete the given stack from storage.
     *
     * @param Stack $stack The stack to delete.
     * @return void
     */
    public function deleteModel(Stack $stack): void
    {
        $stack->delete();
    }

    /**
     * Delete the given stack from storage.
     *
     * @param string|int $id The stack to delete.
     * @return void
     */
    public function deleteById(string|int $id): void
    {
        $stack = $this->find($id);
        $stack->delete();
    }

    /**
     * Normalize data for creating or updating stacks.
     *
     * @param array $data The input data.
     * @return array Sanitized and normalized data.
     */
    private function normalize(array $data): array
    {
        return $this->onlyFields($data, ['name', 'image']);
    }
}
