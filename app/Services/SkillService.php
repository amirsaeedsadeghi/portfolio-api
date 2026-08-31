<?php

namespace App\Services;

use App\Http\Filters\QueryFilterInterface;
use App\Models\Skill;
use App\Repositories\Interfaces\SkillRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class SkillService
 *
 * Handles business logic for managing skills, acting as a layer
 * between controllers and the data access layer (repository).
 */
class SkillService
{
    /**
     * SkillService constructor.
     *
     * @param SkillRepositoryInterface $repository The repository implementation for skill data access.
     */
    public function __construct(protected SkillRepositoryInterface $repository) {}

    /**
     * Retrieve all skills matching the specified filter.
     *
     * @param QueryFilterInterface $filters The filter instance to apply.
     * @return Collection<int, Skill> A collection of matched skills.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return $this->repository->allWithOrder($filters);
    }

    /**
     * Retrieve a paginated list of skills with filtering applied.
     *
     * @param QueryFilterInterface $filters Filter logic to apply to the skill query.
     * @param int $perPage Number of items per page. Default is 15.
     * @return LengthAwarePaginator Paginated skills.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator
    {

        return $this->repository->paginate($filters, $perPage);
    }

    /**
     * Find a skill by its ID or UUID.
     *
     * @param string|int $id The skill ID or UUID.
     * @return Skill The found skill instance.
     *
     * @throws ModelNotFoundException If the skill is not found.
     */
    public function findById(string|int $id): Skill
    {
        $skill = $this->repository->find($id);
        Gate::authorize('view', $skill);
        return $skill;
    }

    /**
     * Create a new skill in the database.
     *
     * @param array $data Associative array of skill data.
     *                    Required: 'name'
     *                    Optional: 'level', 'description'
     *
     * @return Skill The newly created skill instance.
     */
    public function create(array $data): Skill
    {
        Gate::authorize('create', Skill::class);
        return $this->repository->create($data);
    }

    /**
     * Update the specified skill's information by ID.
     *
     * @param string|int $id The skill ID to update.
     * @param array $data Fields to update.
     * @return Skill The updated skill instance.
     */
    public function update(string|int $id, array $data): Skill
    {
        $skill = $this->repository->find($id);
        Gate::authorize('update', $skill);
        return $this->repository->updateModel($skill, $data);
    }

    /**
     * Delete a skill from the system (hard delete) by ID.
     *
     * @param string|int $id The skill ID to delete.
     * @return void
     */
    public function delete(string|int $id): void
    {
        $skill = $this->repository->find($id);
        Gate::authorize('delete', $skill);
        $this->repository->deleteModel($skill);
    }
}
