<?php

namespace App\Repositories;

use App\Models\ProjectImage;
use App\Traits\NormalizesData;
use Illuminate\Support\Facades\DB;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\ProjectImageRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ProjectImageRepository
 *
 * Repository responsible for managing ProjectImage records.
 *
 * Responsibilities:
 * - Fetching project images with or without filters.
 * - Counting images for a specific project.
 * - Creating, updating, deleting single or multiple images.
 * - Enforcing project ownership validation when updating/deleting images.
 *
 * Utilizes the NormalizesData trait to sanitize and cast input data.
 */
class ProjectImageRepository implements ProjectImageRepositoryInterface
{
    use NormalizesData;

    /**
     * Retrieve all images for a specific project matching the given filter.
     *
     * @param QueryFilterInterface $filters Filters to apply.
     * @param string|int $projectId The project ID.
     * @return Collection<int, ProjectImage> A collection of project images.
     */
    public function all(QueryFilterInterface $filters, string|int $projectId): Collection
    {
        return ProjectImage::where('project_id', $projectId)->filter($filters)->get();
    }

    /**
     * Count all images for a given project.
     *
     * @param string|int $projectId The project ID.
     * @return int Number of images belonging to the project.
     */
    public function countByProject(string|int $projectId): int
    {
        return ProjectImage::where('project_id', $projectId)->count();
    }

    /**
     * Find an image by its ID.
     *
     * @param string|int $id The image ID.
     * @return ProjectImage The found project image.
     *
     * @throws ModelNotFoundException If the image does not exist.
     */
    public function findById(string|int $id): ProjectImage
    {
        return ProjectImage::findOrFail($id);
    }

    /**
     * Find an image by its ID and parent project ID.
     *
     * @param string|int $id The image ID.
     * @param string|int $projectId The parent project ID.
     * @return ProjectImage The found project image.
     *
     * @throws ModelNotFoundException If no matching image exists.
     */
    public function findByIdAndProject(string|int $id, string|int $projectId): ProjectImage
    {
        return ProjectImage::where('id', $id)->where('project_id', $projectId)->firstOrFail();
    }

    /**
     * Create a new project image.
     *
     * @param array<string, mixed> $data The image payload.
     * @return ProjectImage The newly created project image.
     */
    public function create(array $data): ProjectImage
    {
        return ProjectImage::create($this->normalize($data));
    }

    /**
     * Update an existing image model and validate project ownership.
     *
     * @param ProjectImage $projectImage The image model to update.
     * @param string|int $projectId The parent project ID.
     * @param array<string, mixed> $data The new payload.
     * @return ProjectImage The updated image.
     *
     * @throws ModelNotFoundException If the image does not belong to the given project.
     */
    public function updateModel(ProjectImage $projectImage, string|int $projectId, array $data): ProjectImage
    {
        if ((int)$projectImage->project_id !== (int)$projectId) {
            throw (new ModelNotFoundException())->setModel(ProjectImage::class, [$projectImage->id]);
        }

        $projectImage->fill($this->normalize($data, true))->save();
        return $projectImage->refresh();
    }

    /**
     * Update an image by ID and project ID.
     *
     * @param string|int $id The image ID.
     * @param string|int $projectId The project ID.
     * @param array<string, mixed> $data The new payload.
     * @return ProjectImage The updated image.
     *
     * @throws ModelNotFoundException If the image does not exist or project mismatch occurs.
     */
    public function updateById(string|int $id, string|int $projectId, array $data): ProjectImage
    {
        $projectImage = $this->findByIdAndProject($id, $projectId);
        $projectImage->fill($this->normalize($data, true))->save();
        return $projectImage->refresh();
    }

    /**
     * Delete a project image by model reference and validate ownership.
     *
     * @param ProjectImage $projectImage The image model.
     * @param string|int $projectId The parent project ID.
     * @return void
     *
     * @throws ModelNotFoundException If the image does not belong to the given project.
     */
    public function deleteModel(ProjectImage $projectImage, string|int $projectId): void
    {
        if ((int)$projectImage->project_id !== (int) $projectId) {
            throw (new ModelNotFoundException())->setModel(ProjectImage::class, [$projectImage->id]);
        }
        $projectImage->delete();
    }

    /**
     * Delete a project image by ID and project ID.
     *
     * @param string|int $id The image ID.
     * @param string|int $projectId The project ID.
     * @return void
     *
     * @throws ModelNotFoundException If the image does not exist or project mismatch occurs.
     */
    public function deleteById(string|int $id, string|int $projectId): void
    {
        $projectImage = $this->findByIdAndProject($id, $projectId);
        $projectImage->delete();
    }

    /**
     * Create multiple images for a project in a single transaction.
     *
     * Expects:
     * - project_id (int)
     * - images (array of image payloads)
     *
     * @param array<string, mixed> $data Bulk payload containing project_id and images.
     * @return Collection<int, ProjectImage> The created project images.
     */
    public function createBulk(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            $project_id = $data['project_id'];
            $projectImages = new Collection([]);

            foreach ($data['images'] as $item) {
                $item['project_id'] = $project_id;
                $projectImages[] = $this->create($item);
            }

            return $projectImages;
        });
    }

    /**
     * Normalize input data for project images.
     *
     * @param array<string, mixed> $data The input data.
     * @param bool $partial Whether this is a partial update.
     * @return array<string, mixed> Sanitized and normalized data.
     */
    private function normalize(array $data, bool $partial = false): array
    {
        $data = $this->onlyFields($data, ['project_id', 'image', 'alt', 'order']);

        if (!$partial) {
            $data = $this->mergeDefaults($data, ['order' => 1]);
        }

        return $this->forceCast($data, [
            'project_id' => 'int',
            'image'      => 'string',
            'alt'        => 'string',
            'order'      => 'int',
        ]);
    }
}
