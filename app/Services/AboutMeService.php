<?php

namespace App\Services;

use App\Models\AboutMe;
use App\Repositories\Interfaces\AboutMeRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class AboutMeService
 *
 * Business layer for managing the single “About Me” profile entity.
 * Acts as a thin layer between controllers and the repository, and
 * enforces authorization on mutating operations.
 */
class AboutMeService
{
    /**
     * AboutMeService constructor.
     *
     * @param AboutMeRepositoryInterface $repository The repository implementation for AboutMe data access.
     */
    public function __construct(protected AboutMeRepositoryInterface $repository) {}

    /**
     * Retrieve the current AboutMe record (singleton).
     *
     * Note: No authorization gate is applied here by default, assuming
     * public read access for the profile page. If you require access
     * control, consider adding a policy check (e.g., 'viewAny' or 'view').
     *
     * @return AboutMe|null The AboutMe model if it exists, otherwise null.
     */
    public function find(): ?AboutMe
    {
        return $this->repository->find();
    }

    /**
     * Create or update the AboutMe record.
     *
     * If no record exists, a new one is created (requires 'create' on AboutMe::class).
     * Otherwise, the existing record is updated (requires 'update' on the model).
     *
     * @param array $data Associative array of AboutMe data.
     *                    Example keys: 'title', 'summary', 'content', 'avatar'
     * @return AboutMe The created or updated AboutMe instance.
     *
     * @throws AuthorizationException If the current user is not authorized.
     */
    public function save(array $data): AboutMe
    {
        $aboutMe = $this->repository->find();

        if (is_null($aboutMe)) {
            Gate::authorize('create', AboutMe::class);
            return $this->repository->create($data);
        }

        Gate::authorize('update', $aboutMe);
        return $this->repository->updateModel($aboutMe, $data);
    }

    /**
     * Delete the AboutMe record (if any).
     *
     * Requires 'delete' authorization on the existing model. If there is no
     * record, this method is a no-op.
     *
     * @return void
     *
     * @throws AuthorizationException If the current user is not authorized to delete.
     */
    public function delete(): void
    {
        $aboutMe = $this->repository->find();

        // No record to delete – silently return (idempotent behavior).
        if (is_null($aboutMe)) {
            return;
        }

        Gate::authorize('delete', $aboutMe);
        $this->repository->deleteModel($aboutMe);
    }
}
