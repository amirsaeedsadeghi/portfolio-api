<?php

namespace App\Repositories\Interfaces;

use App\Http\Filters\QueryFilterInterface;
use App\Models\ProjectImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface ProjectImageRepositoryInterface
 *
 * Defines the contract for managing ProjectImage records.
 * Implementing classes must provide methods for retrieving,
 * creating (single or bulk), updating, deleting, and counting
 * images associated with projects.
 */
interface ProjectImageRepositoryInterface
{
    /**
     * Retrieve all project images for a given project ID,
     * with optional filtering applied.
     *
     * @param QueryFilterInterface $filters The applied filters (e.g. order).
     * @param string|int $project_id The associated project ID.
     * @return Collection<int, ProjectImage> A collection of project images.
     */
    public function all(QueryFilterInterface $filters, string|int $project_id): Collection;

    /**
     * Find a project image by its ID.
     *
     * @param string|int $id The image ID.
     * @return ProjectImage The found project image.
     *
     * @throws ModelNotFoundException If the project image does not exist.
     */
    public function findById(string|int $id): ProjectImage;

    /**
     * Find a project image by ID and project ID.
     *
     * @param string|int $id The image ID.
     * @param string|int $projectId The associated project ID.
     * @return ProjectImage The found project image.
     *
     * @throws ModelNotFoundException If the project image does not exist.
     */
    public function findByIdAndProject(string|int $id, string|int $projectId): ProjectImage;

    /**
     * Count all images belonging to a given project.
     *
     * @param string|int $projectId The project ID.
     * @return int The number of images for the project.
     */
    public function countByProject(string|int $projectId): int;

    /**
     * Create a new project image record.
     *
     * @param array<string, mixed> $data The image data.
     * @return ProjectImage The newly created project image.
     */
    public function create(array $data): ProjectImage;

    /**
     * Create multiple project images in bulk.
     *
     * @param array<string, mixed> $data The input data containing project_id and images[].
     * @return Collection<int, ProjectImage> A collection of newly created project images.
     */
    public function createBulk(array $data): Collection;

    /**
     * Update an existing project image model.
     *
     * @param ProjectImage $model The image model to update.
     * @param string|int $projectId The associated project ID.
     * @param array<string, mixed> $data The updated image data.
     * @return ProjectImage The updated project image.
     *
     * @throws ModelNotFoundException If the project ID does not match the image's project_id.
     */
    public function updateModel(ProjectImage $model, string|int $projectId, array $data): ProjectImage;

    /**
     * Update a project image by ID and project ID.
     *
     * @param string|int $id The image ID.
     * @param string|int $projectId The associated project ID.
     * @param array<string, mixed> $data The updated image data.
     * @return ProjectImage The updated project image.
     */
    public function updateById(string|int $id, string|int $projectId, array $data): ProjectImage;

    /**
     * Delete a project image using the model instance.
     *
     * @param ProjectImage $model The image model to delete.
     * @param string|int $projectId The associated project ID.
     * @return void
     *
     * @throws ModelNotFoundException If the project ID does not match the image's project_id.
     */
    public function deleteModel(ProjectImage $model, string|int $projectId): void;

    /**
     * Delete a project image by ID and project ID.
     *
     * @param string|int $id The image ID.
     * @param string|int $projectId The associated project ID.
     * @return void
     */
    public function deleteById(string|int $id, string|int $projectId): void;
}
