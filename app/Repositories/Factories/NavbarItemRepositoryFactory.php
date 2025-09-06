<?php

namespace App\Repositories\Factories;

use App\Repositories\Interfaces\NavbarItemRepositoryInterface;
use App\Repositories\NavbarItemRepository;

/**
 * Class NavbarItemRepositoryFactory
 *
 * Factory class responsible for creating instances of NavbarItemRepository.
 * Implements the RepositoryFactoryInterface to provide a unified approach
 * for resolving repository instances dynamically.
 */
class NavbarItemRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of NavbarItemRepository.
     *
     * @return NavbarItemRepositoryInterface A concrete instance of NavbarItemRepository.
     */
    public function make(): NavbarItemRepositoryInterface
    {
        return new NavbarItemRepository();
    }
}
