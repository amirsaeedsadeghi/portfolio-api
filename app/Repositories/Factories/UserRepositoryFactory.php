<?php

namespace App\Repositories\Factories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

/**
 * Class UserRepositoryFactory
 *
 * Factory class responsible for creating instances of UserRepository.
 * Implements the RepositoryFactoryInterface to provide a standardized
 * mechanism for resolving repository instances dynamically.
 *
 * This approach supports loose coupling, easy mocking in tests,
 * and flexible swapping of repository implementations.
 */
class UserRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of UserRepository.
     *
     * @return UserRepositoryInterface A concrete instance of UserRepository.
     */
    public function make(): UserRepositoryInterface
    {
        return new UserRepository();
    }
}
