<?php

namespace App\Repositories;

use App\Http\Filters\QueryFilterInterface;
use App\Models\ContactMe;
use App\Repositories\Interfaces\ContactMeRepositoryInterface;
use App\Traits\NormalizesData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ContactMeRepository
 *
 * Repository responsible for managing ContactMe records.
 *
 * Responsibilities:
 * - Fetching all contact messages with or without pagination.
 * - Finding, creating, updating, and deleting messages.
 * - Applying query filters to support dynamic retrieval.
 *
 * Utilizes the NormalizesData trait to sanitize and cast input data.
 */
class ContactMeRepository implements ContactMeRepositoryInterface
{
    use NormalizesData;

    /**
     * Retrieve all contact messages matching the given filter.
     *
     * @param QueryFilterInterface $filter Filters to apply (optional search or conditions).
     * @return Collection<int, ContactMe> A collection of contact messages.
     */
    public function all(QueryFilterInterface $filter): Collection
    {
        return ContactMe::filter($filter)->get();
    }

    /**
     * Paginate the list of contact messages matching the given filter.
     *
     * @param QueryFilterInterface $filter Filters to apply.
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator Paginated list of contact messages.
     */
    public function paginate(QueryFilterInterface $filter, int $perPage = 15): LengthAwarePaginator
    {
        return ContactMe::filter($filter)->paginate($perPage);
    }

    /**
     * Find a contact message by its ID.
     *
     * @param string|int $id The message ID.
     * @return ContactMe The found message.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the message does not exist.
     */
    public function find(string|int $id): ContactMe
    {
        return ContactMe::findOrFail($id);
    }

    /**
     * Create a new contact message record.
     *
     * @param array<string, mixed> $data The contact message data.
     * @return ContactMe The newly created message.
     */
    public function create(array $data): ContactMe
    {
        return ContactMe::create($this->normalize($data));
    }

    /**
     * Update a contact message by ID.
     *
     * @param string|int $id The message ID.
     * @param array<string, mixed> $data The new data to apply.
     * @return ContactMe The updated message.
     */
    public function updateById(string|int $id, array $data): ContactMe
    {
        $contactMe = $this->find($id);
        $contactMe->fill($this->normalize($data))->save();
        return $contactMe->refresh();
    }

    /**
     * Update a contact message using the model instance.
     *
     * @param ContactMe $contactMe The message model to update.
     * @param array<string, mixed> $data The new data to apply.
     * @return ContactMe The updated message.
     */
    public function updateModel(ContactMe $contactMe, array $data): ContactMe
    {
        $contactMe->fill($this->normalize($data))->save();
        return $contactMe->refresh();
    }

    /**
     * Delete a contact message by ID.
     *
     * @param string|int $id The message ID.
     * @return void
     */
    public function deleteById(string|int $id): void
    {
        $contactMe = $this->find($id);
        $contactMe->delete();
    }

    /**
     * Delete a contact message by model instance.
     *
     * @param ContactMe $contactMe The message model to delete.
     * @return void
     */
    public function deleteModel(ContactMe $contactMe): void
    {
        $contactMe->delete();
    }

    /**
     * Normalize data for creating or updating a contact message.
     *
     * @param array<string, mixed> $data The input data.
     * @return array<string, mixed> Sanitized and normalized data.
     */
    private function normalize(array $data): array
    {
        $data = $this->onlyFields($data, ['name', 'email', 'message_body']);
        return $this->forceCast($data, [
            'name'         => 'string',
            'email'        => 'string',
            'message_body' => 'string',
        ]);
    }
}
