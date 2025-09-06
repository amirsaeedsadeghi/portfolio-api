<?php

namespace App\Repositories\Factories;

use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\AuthRepository;

/**
 * Class AuthRepositoryFactory
 *
 * Factory class responsible for creating instances of AuthRepository.
 * Implements the RepositoryFactoryInterface to provide a unified way
 * to resolve repository instances dynamically.
 */
class AuthRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of AuthRepository.
     *
     * @return AuthRepositoryInterface A concrete instance of AuthRepository.
     */
    public function make(): AuthRepositoryInterface
    {
        return new AuthRepository();
    }
}
