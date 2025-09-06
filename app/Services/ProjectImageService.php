<?php

namespace App\Services;

use App\Models\ProjectImage;
use Illuminate\Support\Facades\Gate;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\ProjectImageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DomainException;

/**
 * Class ProjectImageService
 *
 * Handles business logic for managing project images. All mutating operations
 * are authorized against the parent Project (scoped by slug).
 */
class ProjectImageService
{
    /**
     * ProjectImageService constructor.
     *
     * @param ProjectImageRepositoryInterface $projectImageRepository Repository for project-image data access.
     * @param ProjectRepositoryInterface      $projectRepository      Repository for project data access (slug lookup / scoping).
     */
    public function __construct(
        protected ProjectImageRepositoryInterface $projectImageRepository,
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Retrieve all images of a project (scoped by slug) matching provided filters.
     *
     * @param QueryFilterInterface $filters Filters to apply to the images query.
     * @param string               $slug    Unique slug of the parent project.
     * @return Collection<int, ProjectImage> A collection of matched project images.
     *
     * @throws ModelNotFoundException                 If the project (by slug) is not found.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the current user is not authorized to view the project.
     */
    public function all(QueryFilterInterface $filters, string $slug): Collection
    {
        $project = $this->projectRepository->findBySlug($slug);
        Gate::authorize('view', $project);

        return $this->projectImageRepository->all($filters, $project->id);
    }

    /**
     * Find a project image by its ID.
     *
     * Note: This method does not scope by project slug. Use carefully where global access is intended
     * (e.g., admin flows). For slug-scoped retrieval, prefer repository methods that accept project_id.
     *
     * @param string|int $id Project image ID.
     * @return ProjectImage The found project image.
     *
     * @throws ModelNotFoundException If the image is not found.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the current user is not authorized to view the image.
     */
    public function findById(string|int $id): ProjectImage
    {
        $image = $this->projectImageRepository->findById($id);
        Gate::authorize('view', $image->project);

        return $image;
    }

    /**
     * Bulk-create project images for a project (scoped by slug).
     *
     * @param string $slug  Unique slug of the parent project.
     * @param array  $data  Payload for bulk creation.
     *                     Required: 'images' => array<array{path:string, alt?:string, order?:int}>
     *                     Optional keys per image: 'alt', 'order'
     * @return Collection<int, ProjectImage> The created images.
     *
     * @throws ModelNotFoundException                 If the project (by slug) is not found.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the current user is not authorized to update the project.
     * @throws DomainException                        If creating would exceed the allowed image limit per project.
     */
    public function create(string $slug, array $data): Collection
    {
        $project = $this->projectRepository->findBySlug($slug);
        Gate::authorize('update', $project);

        $this->ensureNotExceedsLimit($project->id, 4);

        $data['project_id'] = $project->id;

        return $this->projectImageRepository->createBulk($data);
    }

    /**
     * Update a project image model with slug scoping.
     *
     * @param string       $slug         Unique slug of the parent project (authorization scope).
     * @param ProjectImage $projectImage The project image instance to update.
     * @param array        $data         Fields to update.
     * @return ProjectImage The updated image.
     *
     * @throws ModelNotFoundException                 If the project (by slug) is not found.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the current user is not authorized to update.
     */
    public function updateModel(string $slug, ProjectImage $projectImage, array $data): ProjectImage
    {
        $project = $this->projectRepository->findBySlug($slug);
        Gate::authorize('update', $project);

        return $this->projectImageRepository->updateModel($projectImage, $project->id, $data);
    }

    /**
     * Update a project image by ID with slug scoping.
     *
     * @param string     $slug Unique slug of the parent project (authorization scope).
     * @param string|int $id   Project image ID.
     * @param array      $data Fields to update.
     * @return ProjectImage The updated image.
     *
     * @throws ModelNotFoundException                 If the project or image is not found.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the current user is not authorized to update.
     */
    public function updateById(string $slug, string|int $id, array $data): ProjectImage
    {
        $project = $this->projectRepository->findBySlug($slug);
        Gate::authorize('update', $project);

        return $this->projectImageRepository->updateById($id, $project->id, $data);
    }

    /**
     * Delete a project image model with slug scoping.
     *
     * @param string       $slug         Unique slug of the parent project (authorization scope).
     * @param ProjectImage $projectImage The project image instance to delete.
     * @return void
     *
     * @throws ModelNotFoundException                 If the project (by slug) is not found.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the current user is not authorized to delete.
     */
    public function deleteModel(string $slug, ProjectImage $projectImage): void
    {
        $project = $this->projectRepository->findBySlug($slug);
        Gate::authorize('delete', $project);

        $this->projectImageRepository->deleteModel($projectImage, $project->id);
    }

    /**
     * Delete a project image by ID with slug scoping.
     *
     * @param string     $slug Unique slug of the parent project (authorization scope).
     * @param string|int $id   Project image ID.
     * @return void
     *
     * @throws ModelNotFoundException                 If the project or image is not found.
     * @throws \Illuminate\Auth\Access\AuthorizationException If the current user is not authorized to delete.
     */
    public function deleteById(string $slug, string|int $id): void
    {
        $project = $this->projectRepository->findBySlug($slug);
        Gate::authorize('delete', $project);

        $this->projectImageRepository->deleteById($id, $project->id);
    }

    /**
     * Ensure the project does not exceed the maximum allowed image count.
     *
     * @param int $projectId Project ID.
     * @param int $limit     Max number of images allowed per project.
     * @return void
     *
     * @throws DomainException If the current count is already at or above the limit.
     */
    protected function ensureNotExceedsLimit(int $projectId, int $limit): void
    {
        $count = $this->projectImageRepository->countByProject($projectId);

        if ($count >= $limit) {
            throw new DomainException("A project cannot have more than {$limit} images.");
        }
    }
}
