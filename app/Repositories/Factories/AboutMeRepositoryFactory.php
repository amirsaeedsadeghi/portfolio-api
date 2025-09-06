<?php

namespace App\Repositories\Factories;

use App\Repositories\AboutMeRepository;
use App\Repositories\Interfaces\AboutMeRepositoryInterface;

/**
 * Class AboutMeRepositoryFactory
 *
 * Factory class responsible for creating instances of AboutMeRepository.
 * Implements the RepositoryFactoryInterface to provide a unified way
 * to resolve repository instances dynamically.
 */
class AboutMeRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of AboutMeRepository.
     *
     * @return AboutMeRepositoryInterface A concrete instance of AboutMeRepository.
     */
    public function make(): AboutMeRepositoryInterface
    {
        return new AboutMeRepository();
    }
}
