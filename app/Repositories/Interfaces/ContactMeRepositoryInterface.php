<?php

namespace App\Repositories\Interfaces;

use App\Http\Filters\QueryFilterInterface;
use App\Models\ContactMe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface ContactMeRepositoryInterface
 *
 * Defines the contract for managing ContactMe records.
 * Implementing classes must provide methods for retrieving,
 * creating, updating, deleting, and filtering contact messages.
 */
interface ContactMeRepositoryInterface
{
    /**
     * Retrieve all contact messages matching the given filter.
     *
     * @param QueryFilterInterface $filter The applied query filter (e.g. name, email, messageBody).
     * @return Collection<int, ContactMe> A collection of contact messages.
     */
    public function all(QueryFilterInterface $filter): Collection;

    /**
     * Paginate the list of contact messages matching the given filter.
     *
     * @param QueryFilterInterface $filter The filter to apply.
     * @param int $perPage Number of messages per page.
     * @return LengthAwarePaginator A paginated list of contact messages.
     */
    public function paginate(QueryFilterInterface $filter, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a contact message by its ID.
     *
     * @param string|int $id The message ID.
     * @return ContactMe The found contact message instance.
     *
     * @throws ModelNotFoundException If the contact message does not exist.
     */
    public function find(string|int $id): ContactMe;

    /**
     * Create a new contact message record.
     *
     * @param array<string, mixed> $data The contact message data to persist.
     * @return ContactMe The newly created message.
     */
    public function create(array $data): ContactMe;

    /**
     * Update a contact message by ID.
     *
     * @param string|int $id The message ID.
     * @param array<string, mixed> $data The updated data.
     * @return ContactMe The updated contact message.
     */
    public function updateById(string|int $id, array $data): ContactMe;

    /**
     * Update a contact message using the model instance.
     *
     * @param ContactMe $contactMe The contact message to update.
     * @param array<string, mixed> $data The updated data.
     * @return ContactMe The updated contact message.
     */
    public function updateModel(ContactMe $contactMe, array $data): ContactMe;

    /**
     * Permanently delete a contact message by ID.
     *
     * @param string|int $id The message ID.
     * @return void
     */
    public function deleteById(string|int $id): void;

    /**
     * Permanently delete a contact message using the model instance.
     *
     * @param ContactMe $contactMe The contact message to delete.
     * @return void
     */
    public function deleteModel(ContactMe $contactMe): void;
}
