<?php

namespace App\Repositories\Factories;

use App\Repositories\Interfaces\ProjectImageRepositoryInterface;
use App\Repositories\ProjectImageRepository;

/**
 * Class ProjectImageRepositoryFactory
 *
 * Factory class responsible for creating instances of ProjectImageRepository.
 * Implements the RepositoryFactoryInterface to provide a standardized
 * mechanism for resolving repository instances dynamically.
 *
 * This approach supports loose coupling, easy mocking in tests,
 * and flexible swapping of repository implementations.
 */
class ProjectImageRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of ProjectImageRepository.
     *
     * @return ProjectImageRepositoryInterface A concrete instance of ProjectImageRepository.
     */
    public function make(): ProjectImageRepositoryInterface
    {
        return new ProjectImageRepository();
    }
}
