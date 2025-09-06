<?php

namespace App\Repositories;

use App\Models\User;
use App\Http\Filters\QueryFilterInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\NormalizesData;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class UserRepository
 *
 * Concrete implementation of the UserRepositoryInterface.
 * Provides data access methods for the User model, including filtering,
 * pagination, and basic CRUD operations.
 */
class UserRepository implements UserRepositoryInterface
{

    use NormalizesData;
    /**
     * Create a new user in the database.
     *
     * @param array $data The user data to be persisted.
     * @return User The newly created user model instance.
     */
    public function create(array $data): User
    {
        return User::create($this->normalize($data));
    }

    /**
     * Update an existing user's data.
     *
     * @param string|int $id The user model to update.
     * @param array $data The updated data.
     * @return User The updated and refreshed user model.
     */
    public function updateById(string|int $id, array $data): User
    {
        $user = $this->find($id);
        $user->fill($this->normalizePartial($data))->save();
        return $user->refresh();
    }

    /**
     * Update an existing user's data.
     *
     * @param User $user The user model to update.
     * @param array $data The updated data.
     * @return User The updated and refreshed user model.
     */
    public function updateModel(User $user, array $data): User
    {
        $user->fill($this->normalizePartial($data))->save();
        return $user->refresh();
    }

    /**
     * Get a paginated list of users matching the given filter.
     *
     * @param QueryFilterInterface $filters The filter to apply to the query.
     * @param int $perPage The number of users per page.
     * @return LengthAwarePaginator The paginated result set.
     */
    public function paginate(QueryFilterInterface $filters, int $perPage = 15): LengthAwarePaginator
    {
        return User::filter($filters)->paginate($perPage);
    }

    /**
     * Retrieve all users matching the given filter.
     *
     * @param QueryFilterInterface $filters The query filter to apply.
     * @return Collection<int, User> A collection of matching user models.
     */
    public function all(QueryFilterInterface $filters): Collection
    {
        return User::filter($filters)->get();
    }

    /**
     * Find a user by ID or UUID.
     *
     * @param int|string $id The user ID or UUID.
     * @return User The user model instance.
     *
     * @throws ModelNotFoundException If no user is found with the given ID.
     */
    public function find(int|string $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Delete a user from the database.
     *
     * @param string|int $id The user model to delete.
     * @return void
     */
    public function deleteById(string|int $id): void
    {
        $user = $this->find($id);
        $user->delete();
    }

    /**
     * Delete a user from the database.
     *
     * @param User $user The user model to delete.
     * @return void
     */
    public function deleteModel(User $user): void
    {
        $user->delete();
    }

    /**
     * Normalize complete User data with field filtering, defaults, and type casting.
     *
     * @param array $data Raw input data (possibly camelCase keys).
     * @return array Normalized snake_case data ready for persistence.
     */
    private function normalize(array $data): array
    {
        $data = $this->onlyFields($data, ['name', 'email', 'password', 'image']);
        $data =  $this->mergeDefaults($data, ['image' => null]);
        return $this->forceCast($data, ['name' => 'string', 'email' => 'string', 'password' => 'string']);
    }

    /**
     * Normalize partial User data for update operations.
     *
     * Filters allowed fields and applies type casting,
     * but does not merge default values for missing keys.
     *
     * @param array $data Partial input data (possibly camelCase keys).
     * @return array Normalized snake_case data ready for update.
     */
    private function normalizePartial(array $data): array
    {
        $data = $this->onlyFields($data, ['name', 'email', 'password', 'image']);
        return $this->forceCast($data, ['name' => 'string', 'email' => 'string', 'password' => 'string']);
    }
}
