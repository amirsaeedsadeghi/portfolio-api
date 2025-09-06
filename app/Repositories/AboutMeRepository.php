<?php

namespace App\Repositories;

use App\Models\AboutMe;
use App\Repositories\Interfaces\AboutMeRepositoryInterface;
use App\Traits\NormalizesData;

/**
 * Class AboutMeRepository
 *
 * Repository class for managing AboutMe data.
 * Provides CRUD operations with automatic data normalization,
 * including type casting and default value handling.
 */
class AboutMeRepository implements AboutMeRepositoryInterface
{
    use NormalizesData;

    /**
     * Retrieve the first AboutMe record.
     *
     * @return AboutMe|null The first AboutMe record, or null if not found.
     */
    public function find(): ?AboutMe
    {
        return AboutMe::firstOrFail();
    }

    /**
     * Create a new AboutMe record with normalized data.
     *
     * @param array $data The input data for the AboutMe model.
     * @return AboutMe The newly created AboutMe record.
     */
    public function create(array $data): AboutMe
    {
        $aboutMe = AboutMe::create($this->normalize($data));
        return $aboutMe;
    }

    /**
     * Update an existing AboutMe record with new data.
     *
     * @param array $data The new data for the model.
     * @return AboutMe The updated and refreshed AboutMe instance.
     */
    public function update(array $data): AboutMe
    {
        $aboutMe = AboutMe::firstOrFail();
        $aboutMe->fill($this->normalizePartial($data))->save();
        return $aboutMe->refresh();
    }

    /**
     * Update an existing AboutMe record with new data.
     *
     * @param AboutMe $aboutMe The AboutMe model.
     * @param array $data The new data for the model.
     * @return AboutMe The updated and refreshed AboutMe instance.
     */
    public function updateModel(AboutMe $aboutMe, array $data): AboutMe
    {
        $aboutMe->fill($this->normalizePartial($data))->save();
        return $aboutMe->refresh();
    }

    /**
     * Delete the first AboutMe record if it exists.
     *
     * @return void
     */
    public function delete(): void
    {
        $aboutMe = AboutMe::first();
        if ($aboutMe) {
            $aboutMe->delete();
        }
    }

    /**
     * Delete the first AboutMe record if it exists.
     *
     * @param $aboutMe The AboutMe Model.
     * @return void
     */
    public function deleteModel(AboutMe $aboutMe): void
    {
        if ($aboutMe) {
            $aboutMe->delete();
        }
    }

    /**
     * Normalize complete AboutMe data with field filtering, defaults, and type casting.
     *
     * @param array $data Raw input data (possibly camelCase keys).
     * @return array Normalized snake_case data ready for persistence.
     */
    private function normalize(array $data): array
    {
        $data = $this->onlyFields($data, [
            'title',
            'summary',
            'location',
            'years_of_experience',
            'language',
            'currently_learning',
            'availability',
            'cv_url',
            'linkedin_url',
            'github_url',
            'portfolio_image',
        ]);

        $data = $this->mergeDefaults($data, [
            'availability'     => true,
            'cv_url'           => null,
            'linkedin_url'     => null,
            'github_url'       => null,
            'portfolio_image'  => null,
        ]);

        return $this->forceCast($data, [
            'years_of_experience' => 'int',
            'availability'        => 'bool',
            'language'            => 'json',
        ]);
    }

    /**
     * Normalize partial AboutMe data for update operations.
     *
     * Filters allowed fields and applies type casting,
     * but does not merge default values for missing keys.
     *
     * @param array $data Partial input data (possibly camelCase keys).
     * @return array Normalized snake_case data ready for update.
     */
    private function normalizePartial(array $data): array
    {
        $data = $this->onlyFields($data, [
            'title',
            'summary',
            'location',
            'years_of_experience',
            'language',
            'currently_learning',
            'availability',
            'cv_url',
            'linkedin_url',
            'github_url',
            'portfolio_image',
        ]);

        return $this->forceCast($data, [
            'years_of_experience' => 'int',
            'availability'        => 'bool',
            'language'            => 'json',
        ]);
    }
}
