<?php

namespace App\Repositories\Factories;

use App\Repositories\ContactMeRepository;
use App\Repositories\Interfaces\ContactMeRepositoryInterface;


/**
 * Class ContactMeRepositoryFactory
 *
 * Factory class responsible for creating instances of ContactMeRepository.
 * Implements the RepositoryFactoryInterface to provide a unified way
 * to resolve repository instances dynamically.
 */
class ContactMeRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of ContactMeRepository.
     *
     * @return ContactMeRepositoryInterface A concrete instance of ContactMeRepository.
     */
    public function make(): ContactMeRepositoryInterface
    {
        return new ContactMeRepository();
    }
}
