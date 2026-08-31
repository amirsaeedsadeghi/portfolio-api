<?php

namespace App\Repositories\Interfaces;

use App\Http\Filters\QueryFilterInterface;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface SkillRepositoryInterface
 *
 * Defines the contract for interacting with Skill entities in the data layer.
 * Provides methods for listing, paginating, finding, creating, updating, and deleting skills.
 */
interface SkillRepositoryInterface
{
    /**
     * Retrieve all skills with optional filtering.
     *
     * @param QueryFilterInterface $filters Query filters to apply.
     * @return Collection<int, Skill> A collection of Skill models.
     */
    public function all(QueryFilterInterface $filters): Collection;

    /**
     * Paginate skills with optional filtering.
     *
     * @param QueryFilterInterface $filters Query filters to apply.
     * @param int $perPage Number of items per page. Defaults to 15.
     * @return LengthAwarePaginator Paginated list of Skill models.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a skill by its primary key.
     *
     * @param string|int $id The primary key of the skill.
     * @return Skill The found Skill model.
     *
     * @throws ModelNotFoundException If no matching skill is found.
     */
    public function find(string|int $id): Skill;

    /**
     * Create a new skill.
     *
     * @param array $data Key-value pairs of skill attributes.
     * @return Skill The newly created Skill model.
     */
    public function create(array $data): Skill;

    /**
     * Update an existing skill.
     *
     * @param string|int $id The Skill model to update.
     * @param array $data Key-value pairs of updated attributes.
     * @return Skill The updated Skill model.
     */
    public function updateById(string|int $id, array $data): Skill;

    /**
     * Update an existing skill.
     *
     * @param Skill $model The Skill model to update.
     * @param array $data Key-value pairs of updated attributes.
     * @return Skill The updated Skill model.
     */
    public function updateModel(Skill $model, array $data): Skill;

    /**
     * Delete a skill from storage.
     *
     * @param string|int $id The Skill model to delete.
     * @return void
     */
    public function deleteById(string|int $id): void;

    /**
     * Delete a skill from storage.
     *
     * @param Skill $model The Skill model to delete.
     * @return void
     */
    public function deleteModel(Skill $model): void;

    /**
     * Retrieve all skills with optional filtering, ordered by position or a defined field.
     *
     * @param QueryFilterInterface $filter Query filters to apply.
     * @return Collection<int, Skill> A collection of ordered Skill models.
     */
    public function allWithOrder(QueryFilterInterface $filter): Collection;
}
