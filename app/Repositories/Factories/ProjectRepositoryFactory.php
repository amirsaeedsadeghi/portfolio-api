<?php

namespace App\Repositories\Factories;

use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\ProjectRepository;

/**
 * Class ProjectRepositoryFactory
 *
 * Factory class responsible for creating instances of ProjectRepository.
 * Implements the RepositoryFactoryInterface to provide a standardized
 * mechanism for resolving repository instances dynamically.
 *
 * This approach supports loose coupling, easy mocking in tests,
 * and flexible swapping of repository implementations.
 */
class ProjectRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of ProjectRepository.
     *
     * @return ProjectRepositoryInterface A concrete instance of ProjectRepository.
     */
    public function make(): ProjectRepositoryInterface
    {
        return new ProjectRepository();
    }
}
