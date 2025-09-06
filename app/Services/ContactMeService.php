<?php

namespace App\Services;

use App\Http\Filters\QueryFilterInterface;
use App\Models\ContactMe;
use App\Repositories\Interfaces\ContactMeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ContactMeService
 *
 * Handles business logic for managing contact messages submitted via the site,
 * acting as a layer between controllers and the data access layer (repository).
 */
class ContactMeService
{
    /**
     * ContactMeService constructor.
     *
     * @param ContactMeRepositoryInterface $repository The repository implementation for contact message data access.
     */
    public function __construct(protected ContactMeRepositoryInterface $repository) {}

    /**
     * Retrieve all contact messages matching the specified filter.
     *
     * @param QueryFilterInterface $filter The filter instance to apply.
     * @return Collection<int, ContactMe> A collection of matched contact messages.
     */
    public function all(QueryFilterInterface $filter): Collection
    {
        Gate::authorize('viewAny', ContactMe::class);
        return $this->repository->all($filter);
    }

    /**
     * Retrieve a paginated list of contact messages with filtering applied.
     *
     * @param QueryFilterInterface $filter Filter logic to apply to the contact message query.
     * @param int $perPage Number of items per page. Default is 15.
     * @return LengthAwarePaginator Paginated contact messages.
     */
    public function paginate(QueryFilterInterface $filter, int $perPage = 15): LengthAwarePaginator
    {
        Gate::authorize('viewAny', ContactMe::class);
        return $this->repository->paginate($filter, $perPage);
    }

    /**
     * Find a contact message by its ID or UUID.
     *
     * @param string|int $id The contact message ID or UUID.
     * @return ContactMe The found contact message instance.
     *
     * @throws ModelNotFoundException If the contact message is not found.
     */
    public function findById(string|int $id): ContactMe
    {
        $contactMe = $this->repository->find($id);
        Gate::authorize('view', $contactMe);

        return $contactMe;
    }

    /**
     * Create a new contact message in the database.
     *
     * Typically exposed publicly (no authorization gate here). Consider adding
     * throttling/antispam measures at the controller/middleware layer.
     *
     * @param array $data Associative array of contact message data.
     *                    Required: 'name', 'email', 'message'
     *                    Optional: 'subject', 'phone'
     *
     * @return ContactMe The newly created contact message instance.
     */
    public function create(array $data): ContactMe
    {
        return $this->repository->create($data);
    }

    /**
     * Delete a contact message from the system (hard delete) by ID.
     *
     * @param string|int $id The contact message ID to delete.
     * @return void
     *
     * @throws ModelNotFoundException If the contact message is not found.
     */
    public function deleteById(string|int $id): void
    {
        $contactMe = $this->repository->find($id);
        Gate::authorize('delete', $contactMe);

        $this->repository->deleteModel($contactMe);
    }

    /**
     * Delete a contact message from the system (hard delete).
     *
     * @param ContactMe $contactMe The contact message instance to delete.
     * @return void
     */
    public function deleteModel(ContactMe $contactMe): void
    {
        Gate::authorize('delete', $contactMe);
        $this->repository->deleteModel($contactMe);
    }
}
