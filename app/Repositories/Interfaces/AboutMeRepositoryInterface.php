<?php

namespace App\Repositories\Interfaces;

use App\Models\AboutMe;

/**
 * Interface AboutMeRepositoryInterface
 *
 * Defines the contract for the AboutMe repository.
 * Provides an abstraction for performing CRUD operations
 * on the AboutMe model without exposing implementation details.
 */
interface AboutMeRepositoryInterface
{
    /**
     * Retrieve the single AboutMe record.
     *
     * @return AboutMe|null The AboutMe instance if found, otherwise null.
     */
    public function find(): ?AboutMe;

    /**
     * Create a new AboutMe record.
     *
     * @param array $data Key-value pairs of the AboutMe attributes.
     * @return AboutMe The newly created AboutMe instance.
     */
    public function create(array $data): AboutMe;

    /**
     * Update an existing AboutMe record.
     *
     * @param array $data Key-value pairs of the updated attributes.
     * @return AboutMe The updated AboutMe instance.
     */
    public function update(array $data): AboutMe;

    /**
     * Update an existing AboutMe record.
     *
     * @param AboutMe $aboutMe The AboutMe Model
     * @param array $data Key-value pairs of the updated attributes.
     * @return AboutMe The updated AboutMe instance.
     */
    public function updateModel(AboutMe $aboutMe, array $data): AboutMe;

    /**
     * Delete the AboutMe record (if it exists).
     *
     * @return void
     */
    public function delete(): void;

    /**
     * Delete the AboutMe record (if it exists).
     *
     * @param AboutMe $aboutMe The AboutMe Model 
     * @return void
     */
    public function deleteModel(AboutMe $aboutMe): void;
}
