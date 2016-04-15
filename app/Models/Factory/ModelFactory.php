<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 9:34 PM
 */

namespace App\Models\Factory;


use App\Models\AbstractModel;
use App\Models\Repository\ModelRepository;
use App\Models\Repository\NoRepositoryToInstantiateException;
use App\Models\Repository\RepositoryInterface;

class ModelFactory implements FactoryInterface
{
    /**
     * @var ModelRepository
     */
    protected $repository;

   /**
    * {@inheritdoc}
    */
    public function getRepository()
    {
        if (!isset($this->repository)) {
            $this->repository = $this->instantiateRepository();
        }

        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Sets the model on the repository.
     *
     * @param $className
     */
    public function setModel($className)
    {
        $this->repository->setModel($className);
    }

    /**
     * {@inheritdoc}
     */
    public function make(array $attributes = [], array $relations = [])
    {
        $model = $this->getRepository()->newModel($attributes);
        $model = $this->setRelationsForModel($model, $relations);
        $model->save();
        return $model;
    }

    /**
     * Sets the relations on a model.
     *
     * @param AbstractModel $model
     * @param array $relations
     * @return AbstractModel
     */
    protected function setRelationsForModel(AbstractModel $model, array $relations = [])
    {
        foreach ($relations as $key => $value) {
            $model->$key()->associate($value);
        }
        return $model;
    }

    /**
     * Instantiates the repository if it needs to be. Can be overridden.
     *
     * @return ModelRepository
     * @throws NoRepositoryToInstantiateException
     */
    protected function instantiateRepository()
    {
        throw new NoRepositoryToInstantiateException('Can\'t instantiate repository for ModelFactory. Make sure to set repository via setRepository method.');
    }

}