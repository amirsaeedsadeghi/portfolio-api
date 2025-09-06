<?php

namespace App\Repositories\Factories;

use App\Repositories\Interfaces\StackRepositoryInterface;
use App\Repositories\StackRepository;

/**
 * Class StackRepositoryFactory
 *
 * Factory class responsible for creating instances of StackRepository.
 * Implements the RepositoryFactoryInterface to provide a unified way
 * to resolve repository instances dynamically.
 */
class StackRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of StackRepository.
     *
     * @return StackRepositoryInterface A concrete instance of StackRepository.
     */
    public function make(): StackRepositoryInterface
    {
        return new StackRepository();
    }
}
