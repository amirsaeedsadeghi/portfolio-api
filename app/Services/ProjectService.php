<?php

namespace App\Services;

use App\Http\Filters\QueryFilterInterface;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ProjectService
 *
 * Handles business logic for managing projects, acting as a layer
 * between controllers and the data access layer (repository).
 */
class ProjectService
{
    /**
     * ProjectService constructor.
     *
     * @param ProjectRepositoryInterface $repository The repository implementation for project data access.
     */
    public function __construct(protected ProjectRepositoryInterface $repository) {}

    /**
     * Retrieve all projects matching the specified filter.
     *
     * @param QueryFilterInterface $filters The filter instance to apply.
     * @return Collection<int, Project> A collection of matched projects.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return $this->repository->all($filters);
    }

    /**
     * Retrieve a paginated list of projects with filtering applied.
     *
     * @param QueryFilterInterface $filters Filter logic to apply to the project query.
     * @param int $perPage Number of items per page. Default is 15.
     * @return LengthAwarePaginator Paginated projects.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    /**
     * Find a project by its slug with optional filtering/context constraints.
     *
     * Typical use-case: apply visibility or publication-state filters via $filters,
     * then retrieve the single project by its unique slug.
     *
     * @param QueryFilterInterface $filters Filters to apply before fetching the project.
     * @param string $slug The unique slug of the project.
     * @return Project The found project instance.
     *
     * @throws ModelNotFoundException If the project is not found.
     */
    public function findBySlug(QueryFilterInterface $filters, string $slug): Project
    {
        $project = $this->repository->findBySlugAndFilter($filters, $slug);
        // Gate::authorize('view', $project);
        return $project;
    }

    /**
     * Find a project by its ID or UUID.
     *
     * @param string|int $id The project ID or UUID.
     * @return Project The found project instance.
     *
     * @throws ModelNotFoundException If the project is not found.
     */
    public function findById(string|int $id): Project
    {
        $project = $this->repository->findById($id);
        Gate::authorize('view', $project);
        return $project;
    }

    /**
     * Create a new project in the database.
     *
     * @param array $data Associative array of project data.
     *                    Required: 'title', 'slug'
     *                    Optional: 'summary', 'content', 'thumbnail', 'published_at', ...
     *
     * @return Project The newly created project instance.
     */
    public function create(array $data): Project
    {
        Gate::authorize('create', Project::class);
        return $this->repository->create($data);
    }

    /**
     * Update the specified project's information by ID.
     *
     * @param string|int $id The project ID to update.
     * @param array $data Fields to update.
     * @return Project The updated project instance.
     *
     * @throws ModelNotFoundException If the project is not found.
     */
    public function updateById(string|int $id, array $data): Project
    {
        $project = $this->repository->findById($id);
        Gate::authorize('update', $project);
        return $this->repository->updateModel($project, $data);
    }

    /**
     * Update the specified project model.
     *
     * @param Project $project The project instance to update.
     * @param array $data Fields to update.
     * @return Project The updated project instance.
     */
    public function updateModel(Project $project, array $data): Project
    {
        Gate::authorize('update', $project);
        return $this->repository->updateModel($project, $data);
    }

    /**
     * Delete a project from the system (hard delete) by ID.
     *
     * @param string|int $id The project ID to delete.
     * @return void
     *
     * @throws ModelNotFoundException If the project is not found.
     */
    public function deleteById(string|int $id): void
    {
        $project = $this->repository->findById($id);
        Gate::authorize('delete', $project);
        $this->repository->deleteModel($project);
    }

    /**
     * Delete a project from the system (hard delete).
     *
     * @param Project $project The project instance to delete.
     * @return void
     */
    public function deleteModel(Project $project): void
    {
        Gate::authorize('delete', $project);
        $this->repository->deleteModel($project);
    }
}
