<?php

namespace App\Services;

use App\Models\Stack;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\StackRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class StackService
 *
 * Handles business logic for managing technology stacks, acting as a layer
 * between controllers and the data access layer (repository).
 */
class StackService
{
    /**
     * StackService constructor.
     *
     * @param StackRepositoryInterface $repository The repository implementation for stack data access.
     */
    public function __construct(protected StackRepositoryInterface $repository) {}

    /**
     * Retrieve all stacks matching the specified filter.
     *
     * @param QueryFilterInterface $filters The filter instance to apply.
     * @return Collection<int, Stack> A collection of matched stacks.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return $this->repository->all($filters);
    }

    /**
     * Find a stack by its ID or UUID.
     *
     * @param string|int $id The stack ID or UUID.
     * @return Stack The found stack instance.
     *
     * @throws ModelNotFoundException If the stack is not found.
     */
    public function findById(string|int $id): Stack
    {
        $stack = $this->repository->find($id);
        Gate::authorize('view', $stack);
        return $stack;
    }

    /**
     * Retrieve a paginated list of stacks with filtering applied.
     *
     * @param QueryFilterInterface $filters Filter logic to apply to the stack query.
     * @param int $perPage Number of items per page. Default is 15.
     * @return LengthAwarePaginator Paginated stacks.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    /**
     * Create a new stack in the database.
     *
     * @param array $data Associative array of stack data.
     *                    Required: 'name'
     *                    Optional: 'description'
     *
     * @return Stack The newly created stack instance.
     */
    public function create(array $data): Stack
    {
        Gate::authorize('create', Stack::class);
        return $this->repository->create($data);
    }

    /**
     * Update the specified stack's information by ID.
     *
     * @param string|int $id The stack ID to update.
     * @param array $data Fields to update.
     * @return Stack The updated stack instance.
     */
    public function updateById(string|int $id, array $data): Stack
    {
        $stack = $this->repository->find($id);
        Gate::authorize('update', $stack);
        return $this->repository->updateModel($stack, $data);
    }

    /**
     * Update the specified stack's information.
     *
     * @param Stack $stack The stack instance to update.
     * @param array $data Fields to update.
     * @return Stack The updated stack instance.
     */
    public function updateModel(Stack $stack, array $data): Stack
    {
        Gate::authorize('update', $stack);
        return $this->repository->updateModel($stack, $data);
    }

    /**
     * Delete a stack from the system (hard delete) by ID.
     *
     * @param string|int $id The stack ID to delete.
     * @return void
     */
    public function deleteById(string|int $id): void
    {
        $stack = $this->repository->find($id);
        Gate::authorize('delete', $stack);
        $this->repository->deleteModel($stack);
    }

    /**
     * Delete a stack from the system (hard delete).
     *
     * @param Stack $stack The stack instance to delete.
     * @return void
     */
    public function deleteModel(Stack $stack): void
    {
        Gate::authorize('delete', $stack);
        $this->repository->deleteModel($stack);
    }
}
