<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface UserRepositoryInterface
 *
 * Defines the contract for user data access.
 * Implementing classes must provide methods for retrieving,
 * creating, updating, deleting, and filtering user records.
 */
interface UserRepositoryInterface
{
    /**
     * Find a user by its ID.
     *
     * @param int|string $id The user ID (or UUID, if applicable).
     * @return User The found user instance.
     *
     * @throws ModelNotFoundException If the user does not exist.
     */
    public function find(int|string $id): User;

    /**
     * Retrieve all users matching the given filter.
     *
     * @param QueryFilterInterface $filter The applied query filter (e.g. name, email).
     * @return Collection<int, User> A collection of users matching the filter.
     */
    public function all(QueryFilterInterface $filter): Collection;

    /**
     * Paginate the list of users matching the given filter.
     *
     * @param QueryFilterInterface $filter The filter to apply.
     * @param int $perPage Number of users per page.
     * @return LengthAwarePaginator A paginated list of users.
     */
    public function paginate(QueryFilterInterface $filter, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new user record.
     *
     * @param array $data The user data to persist.
     * @return User The newly created user.
     */
    public function create(array $data): User;

    /**
     * Update the given user model with new data.
     *
     * @param string|int $id The user to update.
     * @param array $data The updated data.
     * @return User The updated user.
     */
    public function updateById(string|int $id, array $data): User;

    /**
     * Update the given user model with new data.
     *
     * @param User $user The user to update.
     * @param array $data The updated data.
     * @return User The updated user.
     */
    public function updateModel(User $user, array $data): User;

    /**
     * Permanently delete the given user from storage.
     *
     * @param string|int $id The user to delete.
     * @return void
     */
    public function deleteById(string|int $id): void;

    /**
     * Permanently delete the given user from storage.
     *
     * @param User $user The user to delete.
     * @return void
     */
    public function deleteModel(User $user): void;
}
