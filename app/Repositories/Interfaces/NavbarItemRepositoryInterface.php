<?php

namespace App\Repositories\Interfaces;

use App\Http\Filters\QueryFilterInterface;
use App\Models\NavbarItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface NavbarItemRepositoryInterface
 *
 * Defines the contract for handling all data operations related to NavbarItem entities.
 * Provides an abstraction for querying, creating, updating, and deleting navbar items
 * with support for filtering and ordered retrieval.
 */
interface NavbarItemRepositoryInterface
{
    /**
     * Retrieve all navbar items with optional filtering.
     *
     * @param QueryFilterInterface $filter Query filters to apply.
     * @return Collection<int, NavbarItem> A collection of NavbarItem models.
     */
    public function all(QueryFilterInterface $filter): Collection;

    /**
     * Find a specific navbar item by its primary key.
     *
     * @param string|int $id The primary key of the navbar item.
     * @return NavbarItem The NavbarItem instance.
     *
     * @throws ModelNotFoundException If no matching navbar item is found.
     */
    public function find(string|int $id): NavbarItem;

    /**
     * Create a new navbar item.
     *
     * @param array $data Key-value pairs of the navbar item attributes.
     * @return NavbarItem The newly created NavbarItem instance.
     */
    public function create(array $data): NavbarItem;

    /**
     * Update an existing navbar item.
     *
     * @param string|int $id The navbar item to update.
     * @param array $data Key-value pairs of updated attributes.
     * @return NavbarItem The updated NavbarItem instance.
     */
    public function updateById(string|int $id, array $data): NavbarItem;

    /**
     * Update an existing navbar item.
     *
     * @param NavbarItem $navbarItem The navbar item to update.
     * @param array $data Key-value pairs of updated attributes.
     * @return NavbarItem The updated NavbarItem instance.
     */
    public function updateModel(NavbarItem $navbarItem, array $data): NavbarItem;

    /**
     * Delete a navbar item from storage.
     *
     * @param string|int $id The navbar item to delete.
     * @return void
     */
    public function deleteById(string|int $id): void;

    /**
     * Delete a navbar item from storage.
     *
     * @param NavbarItem $navbarItem The navbar item to delete.
     * @return void
     */
    public function deleteModel(NavbarItem $navbarItem): void;

    /**
     * Retrieve all navbar items with optional filtering, ordered by position or a defined field.
     *
     * @param QueryFilterInterface $filter Query filters to apply.
     * @return Collection<int, NavbarItem> A collection of ordered NavbarItem models.
     */
    public function allWithOrder(QueryFilterInterface $filter): Collection;
}
