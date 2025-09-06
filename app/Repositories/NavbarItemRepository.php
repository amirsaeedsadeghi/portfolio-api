<?php

namespace App\Repositories;

use App\Models\NavbarItem;
use App\Traits\NormalizesData;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Interfaces\NavbarItemRepositoryInterface;

/**
 * Class NavbarItemRepository
 *
 * Repository responsible for managing NavbarItem records.
 *
 * Responsibilities:
 * - Fetching all items with or without ordering.
 * - Creating, updating, and deleting navigation items.
 * - Applying query filters to support dynamic retrieval.
 *
 * Utilizes the NormalizesData trait to sanitize and cast input data.
 */
class NavbarItemRepository implements NavbarItemRepositoryInterface
{
    use NormalizesData;

    /**
     * Retrieve all navbar items ordered by their 'order' field.
     *
     * @param QueryFilterInterface $filters Filters to apply (optional search, conditions).
     * @return Collection<int, NavbarItem> Ordered collection of navbar items.
     */
    public function allWithOrder(QueryFilterInterface $filters): Collection
    {
        return NavbarItem::filter($filters)->orderBy('order')->get();
    }

    /**
     * Find a navbar item by its ID.
     *
     * @param string|int $id The navbar item ID.
     * @return NavbarItem The found navbar item.
     *
     * @throws ModelNotFoundException If the item does not exist.
     */
    public function find(string|int $id): NavbarItem
    {
        return NavbarItem::findOrFail($id);
    }

    /**
     * Retrieve all navbar items based on the applied filter.
     *
     * @param QueryFilterInterface $filters Filters to apply.
     * @return Collection<int, NavbarItem> A collection of navbar items.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return NavbarItem::filter($filters)->get();
    }

    /**
     * Create a new navbar item.
     *
     * @param array $data The data for the new navbar item.
     * @return NavbarItem The newly created navbar item.
     */
    public function create(array $data): NavbarItem
    {
        return NavbarItem::create($this->normalize($data));
    }

    /**
     * Update an existing navbar item with the provided data.
     *
     * @param string|int $id The navbar item to update.
     * @param array $data The new data to apply.
     * @return NavbarItem The updated and refreshed navbar item.
     */
    public function updateById(string|int $id, array $data): NavbarItem
    {
        $navbarItem = $this->find($id);
        $navbarItem->fill($this->normalizePartial($data))->save();
        return $navbarItem->refresh();
    }

    /**
     * Update an existing navbar item with the provided data.
     *
     * @param NavbarItem $navbarItem The navbar item to update.
     * @param array $data The new data to apply.
     * @return NavbarItem The updated and refreshed navbar item.
     */
    public function updateModel(NavbarItem $navbarItem, array $data): NavbarItem
    {
        $navbarItem->fill($this->normalizePartial($data))->save();
        return $navbarItem->refresh();
    }

    /**
     * Delete the given navbar item from storage.
     *
     * @param string|int $id The navbar item to delete.
     * @return void
     */
    public function deleteById(string|int $id): void
    {
        $navbarItem = $this->find($id);
        $navbarItem->delete();
    }

    /**
     * Delete the given navbar item from storage.
     *
     * @param NavbarItem $navbarItem The navbar item to delete.
     * @return void
     */
    public function deleteModel(NavbarItem $navbarItem): void
    {
        $navbarItem->delete();
    }

    /**
     * Normalize full creation data for navbar items.
     *
     * @param array $data The input data.
     * @return array Sanitized and normalized data.
     */
    private function normalize(array $data): array
    {
        $data = $this->onlyFields($data, ['label', 'link', 'order']);
        $data = $this->mergeDefaults($data, ['order' => 0]);
        return $data;
    }

    /**
     * Normalize partial update data for navbar items.
     *
     * @param array $data The input data for updating.
     * @return array Sanitized and type-cast data.
     */
    private function normalizePartial(array $data): array
    {
        $data = $this->onlyFields($data, ['label', 'link', 'order']);
        return $this->forceCast($data, ['order' => 'int']);
    }
}
