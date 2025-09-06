<?php

namespace App\Repositories;

use App\Models\Project;
use App\Traits\NormalizesData;
use Illuminate\Support\Facades\DB;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Carbon\Carbon;

/**
 * Class ProjectRepository
 *
 * Repository responsible for managing Project records.
 *
 * Responsibilities:
 * - Fetching all projects with or without pagination.
 * - Finding projects by slug or ID (optionally with filters).
 * - Creating, updating, and deleting projects.
 * - Syncing many-to-many relations (stacks) when provided.
 * - Applying query filters to support dynamic retrieval.
 *
 * Utilizes the NormalizesData trait to sanitize and cast input data.
 */
class ProjectRepository implements ProjectRepositoryInterface
{
    use NormalizesData;

    /**
     * Retrieve all projects matching the given filter.
     *
     * @param QueryFilterInterface $filters Filters to apply (search/conditions/sorting).
     * @return Collection<int, Project> A collection of projects.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return Project::filter($filters)->get();
    }

    /**
     * Paginate the list of projects matching the given filter.
     *
     * @param QueryFilterInterface $filters Filters to apply.
     * @param int $perPage Number of items per page. Default: 15.
     * @return LengthAwarePaginator Paginated projects.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Project::filter($filters)->paginate($perPage);
    }

    /**
     * Find a project by slug with additional filters applied.
     *
     * @param QueryFilterInterface $filters Filters to apply before slug constraint.
     * @param string $slug The project slug.
     * @return Project The found project.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no project matches.
     */
    public function findBySlugAndFilter(QueryFilterInterface $filters, string $slug): Project
    {
        return Project::filter($filters)->where('slug', $slug)->firstOrFail();
    }

    /**
     * Find a project by slug.
     *
     * @param string $slug The project slug.
     * @return Project The found project.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no project matches.
     */
    public function findBySlug(string $slug): Project
    {
        return Project::where('slug', $slug)->firstOrFail();
    }

    /**
     * Find a project by ID.
     *
     * @param string|int $id The project ID.
     * @return Project The found project.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the project does not exist.
     */
    public function findById(string|int $id): Project
    {
        return Project::findOrFail($id);
    }

    /**
     * Create a new project and optionally sync stacks.
     *
     * Expects 'stacks' (array of stack IDs) in $data to sync the relation.
     *
     * @param array<string, mixed> $data The project payload (may include 'stacks').
     * @return Project The newly created project with 'stacks' relation loaded.
     */
    public function create(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            $stacks = $data['stacks'] ?? [];
            unset($data['stacks']);

            $project = Project::create($this->normalize($data));

            if (!empty($stacks)) {
                $project->stacks()->sync($stacks);
            }

            return $project->load('stacks');
        });
    }

    /**
     * Update a project by ID and optionally sync stacks.
     *
     * If 'stacks' is present (even empty array), the relation will be synced accordingly.
     *
     * @param string|int $id The project ID.
     * @param array<string, mixed> $data Partial payload (may include 'stacks').
     * @return Project The updated project with 'stacks' relation loaded.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the project does not exist.
     */
    public function updateById(string|int $id, array $data): Project
    {
        return DB::transaction(function () use ($id, $data) {
            $stacks = $data['stacks'] ?? null;
            unset($data['stacks']);

            $project = Project::findOrFail($id);
            $project->fill($this->normalizePartial($data))->save();

            if (!is_null($stacks)) {
                $project->stacks()->sync($stacks);
            }

            return $project->load('stacks');
        });
    }

    /**
     * Update the given project model and optionally sync stacks.
     *
     * If 'stacks' is present (even empty array), the relation will be synced accordingly.
     *
     * @param Project $project The project model to update.
     * @param array<string, mixed> $data Partial payload (may include 'stacks').
     * @return Project The updated project with 'stacks' relation loaded.
     */
    public function updateModel(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {
            $stacks = $data['stacks'] ?? null;
            unset($data['stacks']);

            $project->fill($this->normalizePartial($data))->save();

            if (!is_null($stacks)) {
                $project->stacks()->sync($stacks);
            }

            return $project->load('stacks');
        });
    }

    /**
     * Delete a project by ID.
     *
     * @param string|int $id The project ID.
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the project does not exist.
     */
    public function deleteById(string|int $id): void
    {
        $project = Project::findOrFail($id);
        $project->delete();
    }

    /**
     * Delete the given project model.
     *
     * @param Project $project The project to delete.
     * @return void
     */
    public function deleteModel(Project $project): void
    {
        $project->delete();
    }

    /**
     * Normalize data for creating a project (with defaults and casting).
     *
     * Accepted keys: title, summary, description, primary_image, client, demo_link,
     * github, category, order, start_date, role.
     *
     * Defaults:
     * - client: null
     * - demo_link: null
     * - github: null
     * - order: 1
     * - start_date: today (Y-m-d)
     *
     * Casts:
     * - order: int
     * - start_date: date (Y-m-d)
     * - role: string
     *
     * @param array<string, mixed> $data The input data.
     * @return array<string, mixed> Sanitized and normalized data.
     */
    private function normalize(array $data): array
    {
        $data = $this->onlyFields($data, [
            'title',
            'summary',
            'description',
            'primary_image',
            'client',
            'demo_link',
            'github',
            'category',
            'order',
            'start_date',
            'role',
        ]);

        $data = $this->mergeDefaults($data, [
            'client'     => null,
            'demo_link'  => null,
            'github'     => null,
            'order'      => 1,
            'start_date' => Carbon::today()->toDateString(),
        ]);

        return $this->forceCast($data, [
            'order'      => 'int',
            'start_date' => 'date',
            'role'       => 'string',
        ]);
    }

    /**
     * Normalize data for updating a project (partial update).
     *
     * Accepted keys: title, summary, description, primary_image, client, demo_link,
     * github, category, order, role, start_date.
     *
     * Casts:
     * - order: int
     * - start_date: date (Y-m-d)
     * - role: string
     *
     * @param array<string, mixed> $data The input data.
     * @return array<string, mixed> Sanitized and normalized data.
     */
    private function normalizePartial(array $data): array
    {
        $data = $this->onlyFields($data, [
            'title',
            'summary',
            'description',
            'primary_image',
            'client',
            'demo_link',
            'github',
            'category',
            'order',
            'role',
            'start_date',
        ]);

        return $this->forceCast($data, [
            'order'      => 'int',
            'start_date' => 'date',
            'role'       => 'string',
        ]);
    }
}
