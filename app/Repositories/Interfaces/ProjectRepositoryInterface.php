<?php

namespace App\Repositories\Interfaces;

use App\Http\Filters\QueryFilterInterface;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface ProjectRepositoryInterface
 *
 * Defines the contract for managing Project records.
 * Implementing classes must handle retrieving, creating,
 * updating, deleting, and filtering project data.
 */
interface ProjectRepositoryInterface
{
    /**
     * Retrieve all projects with optional filters applied.
     *
     * @param QueryFilterInterface $filters Filters to apply (e.g. title, client).
     * @return Collection<int, Project> A collection of projects.
     */
    public function all(QueryFilterInterface $filters): Collection;

    /**
     * Retrieve a paginated list of projects with filters applied.
     *
     * @param QueryFilterInterface $filters The filter to apply.
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator Paginated list of projects.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a project by its slug with additional filters applied.
     *
     * @param QueryFilterInterface $filters Filters to apply to the query.
     * @param string $slug The project slug.
     * @return Project The found project instance.
     *
     * @throws ModelNotFoundException If the project is not found.
     */
    public function findBySlugAndFilter(QueryFilterInterface $filters, string $slug): Project;

    /**
     * Find a project by its slug.
     *
     * @param string $slug The project slug.
     * @return Project The found project instance.
     *
     * @throws ModelNotFoundException If the project is not found.
     */
    public function findBySlug(string $slug): Project;

    /**
     * Find a project by its ID.
     *
     * @param string|int $id The project ID.
     * @return Project The found project instance.
     *
     * @throws ModelNotFoundException If the project is not found.
     */
    public function findById(string|int $id): Project;

    /**
     * Create a new project record.
     *
     * @param array<string, mixed> $data The project data to persist.
     * @return Project The newly created project.
     */
    public function create(array $data): Project;

    /**
     * Update a project by its ID.
     *
     * @param string|int $id The project ID.
     * @param array<string, mixed> $data The updated project data.
     * @return Project The updated project.
     */
    public function updateById(string|int $id, array $data): Project;

    /**
     * Permanently delete a project by its ID.
     *
     * @param string|int $id The project ID.
     * @return void
     */
    public function deleteById(string|int $id): void;

    /**
     * Update the given project model with new data.
     *
     * @param Project $project The project model to update.
     * @param array<string, mixed> $data The updated project data.
     * @return Project The updated project instance.
     */
    public function updateModel(Project $project, array $data): Project;

    /**
     * Permanently delete the given project model.
     *
     * @param Project $project The project model to delete.
     * @return void
     */
    public function deleteModel(Project $project): void;

    /**
     * Retrieve a paginated active projects with optional filters applied, ordered by display order.
     *
     * @param QueryFilterInterface $filters Filters to apply to the query.
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator Paginated list of projects.
     */
    public function paginateActiveWithOrder(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Retrieve all active projects with optional filters applied, ordered by display order.
     *
     * @param QueryFilterInterface $filters Filters to apply to the query. 
     * @return Collection<int, Project> A collection of projects.
     */
    public function allActiveWithOrder(QueryFilterInterface $filters): Collection;
}
