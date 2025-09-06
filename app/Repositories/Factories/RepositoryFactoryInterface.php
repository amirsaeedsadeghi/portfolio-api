<?php

namespace App\Repositories\Factories;

/**
 * @template TRepository
 */
interface RepositoryFactoryInterface 
{
    /**
     * @return TRepository
     */
    public function make():mixed;
}
