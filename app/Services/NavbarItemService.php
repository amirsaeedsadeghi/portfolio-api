<?php

namespace App\Services;

use App\Models\NavbarItem;
use Illuminate\Support\Facades\Gate;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Interfaces\NavbarItemRepositoryInterface;

/**
 * Class NavbarItemService
 *
 * Handles business logic for managing navbar items, acting as a layer
 * between controllers and the data access layer (repository).
 */
class NavbarItemService
{
    /**
     * NavbarItemService constructor.
     *
     * @param NavbarItemRepositoryInterface $repository The repository implementation for navbar item data access.
     */
    public function __construct(protected NavbarItemRepositoryInterface $repository) {}

    /**
     * Retrieve all navbar items matching the specified filter with ordering applied.
     *
     * @param QueryFilterInterface $filters The filter instance to apply.
     * @return Collection<int, NavbarItem> A collection of matched navbar items.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return $this->repository->allWithOrder($filters);
    }

    /**
     * Find a navbar item by its ID (or UUID if supported).
     *
     * @param string|int $id The navbar item ID or UUID.
     * @return NavbarItem The found navbar item instance.
     *
     * @throws ModelNotFoundException If the navbar item is not found.
     */
    public function findById(string|int $id): NavbarItem
    {
        $navbarItem = $this->repository->find($id);
        Gate::authorize('view', $navbarItem);
        return $navbarItem;
    }

    /**
     * Create a new navbar item in the database.
     *
     * @param array $data Associative array of navbar item data.
     * @return NavbarItem The newly created navbar item instance.
     */
    public function create(array $data): NavbarItem
    {
        Gate::authorize('create', NavbarItem::class);
        return $this->repository->create($data);
    }

    /**
     * Update the specified navbar item's information.
     *
     * @param string|int $id The navbar item ID to update.
     * @param array $data Fields to update.
     * @return NavbarItem The updated navbar item instance.
     *
     * @throws ModelNotFoundException If the navbar item is not found.
     */
    public function update(string|int $id, array $data): NavbarItem
    {
        $navbarItem = $this->repository->find($id);
        Gate::authorize('update', $navbarItem);
        return $this->repository->updateModel($navbarItem, $data);
    }

    /**
     * Delete a navbar item from the system (hard delete).
     *
     * @param string|int $id The navbar item ID to delete.
     * @return void
     *
     * @throws ModelNotFoundException If the navbar item is not found.
     */
    public function delete(string|int $id): void
    {
        $navbarItem = $this->repository->find($id);
        Gate::authorize('delete', $navbarItem);
        $this->repository->deleteModel($navbarItem);
    }
}
