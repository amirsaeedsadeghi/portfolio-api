<?php

namespace App\Services;

use App\Http\Filters\QueryFilterInterface;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

/**
 * Class UserService
 *
 * Handles business logic for managing users, acting as a layer
 * between controllers and the data access layer (repository).
 */
class UserService
{
    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $repository The repository implementation for user data access.
     */
    public function __construct(protected UserRepositoryInterface $repository) {}

    /**
     * Create a new user in the database.
     *
     * @param array $data Associative array of user data.
     *                    Required: 'name', 'email', 'password'
     *                    Optional: 'image'
     *
     * @return User The newly created user instance.
     */
    public function create(array $data): User
    {
        Gate::authorize('create', User::class);
        return $this->repository->create($data);
    }

    /**
     * Update the specified user's information.
     *
     * @param string|int $id The user ID to update.
     * @param array $data Fields to update.
     * @return User The updated user instance.
     */
    public function updateById(string|int $id, array $data): User
    {
        $user = $this->repository->find($id);
        Gate::authorize('update', $user);
        return $this->repository->updateModel($user, $data);
    }

    /**
     * Update the specified user's information.
     *
     * @param User $user The user ID to update.
     * @param array $data Fields to update.
     * @return User The updated user instance.
     */
    public function updateModel(User $user, array $data): User
    {
        Gate::authorize('update', $user);
        return $this->repository->updateModel($user, $data);
    }

    /**
     * Find a user by their ID or UUID.
     *
     * @param string|int $id The user ID or UUID.
     * @return User The found user instance.
     *
     * @throws ModelNotFoundException If the user is not found.
     */
    public function findById(string|int $id): User
    {
        $user = $this->repository->find($id);
        Gate::authorize('view', $user);
        return $user;
    }

    /**
     * Delete a user from the system (hard delete).
     *
     * @param string|int $id The user ID to delete.
     * @return void
     */
    public function deleteById(string|int $id): void
    {
        $user = $this->repository->find($id);
        Gate::authorize('delete', $user);
        $this->repository->deleteModel($user);
    }

    /**
     * Delete a user from the system (hard delete).
     *
     * @param User $user The user ID to delete.
     * @return void
     */
    public function deleteModel(User $user): void
    {
        Gate::authorize('delete', $user);
        $this->repository->deleteModel($user);
    }

    /**
     * Retrieve all users matching the specified filter.
     *
     * @param QueryFilterInterface $filter The filter instance to apply.
     * @return Collection<int, User> A collection of matched users.
     */
    public function all(QueryFilterInterface $filter): Collection
    {
        Gate::authorize('viewAny', User::class);
        return $this->repository->all($filter);
    }

    /**
     * Retrieve a paginated list of users with filtering applied.
     *
     * @param QueryFilterInterface $filter Filter logic to apply to the user query.
     * @param int $perPage Number of items per page. Default is 15.
     * @return LengthAwarePaginator Paginated users.
     */
    public function paginate(QueryFilterInterface $filter, int $perPage = 15): LengthAwarePaginator
    {
        Gate::authorize('viewAny', User::class);
        return $this->repository->paginate($filter, $perPage);
    }
}
