<?php

namespace App\Repositories\Factories;

use App\Repositories\Interfaces\SkillRepositoryInterface;
use App\Repositories\SkillRepository;

/**
 * Class SkillRepositoryFactory
 *
 * Factory class responsible for creating instances of SkillRepository.
 * Implements the RepositoryFactoryInterface to provide a unified way
 * to resolve repository instances dynamically.
 */
class SkillRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * Create and return an instance of SkillRepository.
     *
     * @return SkillRepositoryInterface A concrete instance of SkillRepository.
     */
    public function make(): SkillRepositoryInterface
    {
        return new SkillRepository();
    }
}
