<?php

namespace App\Repositories;

use App\Models\Skill;
use App\Traits\NormalizesData;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\SkillRepositoryInterface;

/**
 * Class SkillRepository
 *
 * Repository responsible for managing Skill records.
 * 
 * Responsibilities:
 * - Fetching all skills with or without pagination.
 * - Creating, updating, and deleting skills.
 * - Applying query filters to support dynamic retrieval.
 *
 * Utilizes the NormalizesData trait to sanitize and cast input data.
 */
class SkillRepository implements SkillRepositoryInterface
{
    use NormalizesData;

    /**
     * Retrieve all skills ordered by their 'order' field.
     *
     * @param QueryFilterInterface $filters Filters to apply (optional search, conditions).
     * @return Collection<int, Skill> Ordered collection of skills.
     */
    public function allWithOrder(QueryFilterInterface $filters): Collection
    {
        return Skill::filter($filters)->orderBy('order')->get();
    }

    /**
     * Retrieve all skills matching the given filter.
     *
     * @param QueryFilterInterface $filters Filters to apply (optional search or conditions).
     * @return Collection<int, Skill> A collection of skills.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return Skill::filter($filters)->get();
    }

    /**
     * Paginate the list of skills matching the given filter.
     *
     * @param QueryFilterInterface $filters Filters to apply.
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator Paginated list of skills.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Skill::filter($filters)->orderBy('order')->paginate($perPage);
    }

    /**
     * Find a skill by its ID.
     *
     * @param string|int $id The skill ID.
     * @return Skill The found skill.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the skill does not exist.
     */
    public function find(string|int $id): Skill
    {
        return Skill::findOrFail($id);
    }

    /**
     * Create a new skill record.
     *
     * @param array $data The skill data.
     * @return Skill The newly created skill.
     */
    public function create(array $data): Skill
    {
        return Skill::create($this->normalize($data));
    }

    /**
     * Update an existing skill with the provided data.
     *
     * @param Skill $skill The skill to update.
     * @param array $data The new data to apply.
     * @return Skill The updated and refreshed skill.
     */
    public function updateModel(Skill $skill, array $data): Skill
    {
        $skill->fill($this->normalize($data))->save();
        return $skill->refresh();
    }

    /**
     * Update an existing skill with the provided data.
     *
     * @param string|int $id The skill to update.
     * @param array $data The new data to apply.
     * @return Skill The updated and refreshed skill.
     */
    public function updateById(string|int $id, array $data): Skill
    {
        $skill = $this->find($id);
        $skill->fill($this->normalize($data))->save();
        return $skill->refresh();
    }

    /**
     * Delete the given skill from storage.
     *
     * @param Skill $skill The skill to delete.
     * @return void
     */
    public function deleteModel(Skill $skill): void
    {
        $skill->delete();
    }

    /**
     * Delete the given skill from storage.
     *
     * @param string|int $id The skill to delete.
     * @return void
     */
    public function deleteById(string|int $id): void
    {
        $skill = $this->find($id);
        $skill->delete();
    }

    /**
     * Normalize data for creating or updating skills.
     *
     * @param array $data The input data.
     * @return array Sanitized and normalized data.
     */
    private function normalize(array $data): array
    {
        return $this->onlyFields($data, ['title', 'description']);
    }
}
