<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 9:34 PM
 */

namespace App\Models\Factory;


use App\Models\AbstractModel;
use App\Models\Repository\RepositoryInterface;

interface FactoryInterface
{

    /**
     * Retrieves a repository.
     * Fails if the repository cannot be instantiated.
     *
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * Sets the repository on the factory.
     *
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository);

    /**
     * Makes a new model with the attributes and relations.
     *
     * @param array $attributes
     * @param array $relations
     * @return AbstractModel
     */
    public function make(array $attributes = [], array $relations = []);

}