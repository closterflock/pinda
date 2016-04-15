<?php

namespace App\Models\Repository;


use App\Models\AbstractModel;

class ModelRepository implements RepositoryInterface
{
    /**
     * @var AbstractModel
     */
    protected $className;

    public function __construct($model = null)
    {
        $this->setModel($model);
    }

    /**
     * {@inheritdoc}
     */
    public function setModel($model)
    {
        $this->className = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel()
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id, $with = [])
    {
        $class = $this->getModel();
        return $class::with($with)->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findOrFail($id, $with = [])
    {
        $class = $this->getModel();
        /** @var AbstractModel $model */
        $model = $class::findOrFail($id);
        $model->load($with);
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function findOrNew($id, array $columns = ['*'])
    {
        $class = $this->getModel();
        return $class::findOrNew($id, $columns);
    }

    /**
     * {@inheritdoc}
     */
    public function create($data)
    {
        $class = $this->getModel();
        return $class::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrCreate(array $attributes, $with = [])
    {
        $class = $this->getModel();
        $model = $class::firstOrCreate($attributes);
        $model->load($with);
        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function firstOrNew(array $attributes)
    {
        $class = $this->getModel();
        return $class::firstOrNew($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function all($columns = ['*'])
    {
        $class = $this->getModel();
        return $class::all($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function whereFirst($column, $operator, $value, $with = [])
    {
        $className = $this->getModel();
        return $className::with($with)->where($column, $operator, $value)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function whereGet($column, $operator, $value)
    {
        $className = $this->getModel();
        return $className::where($column, $operator, $value)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function newModel(array $attrs = [])
    {
        $className = $this->getModel();
        return new $className($attrs);
    }

    /**
     * {@inheritdoc}
     */
    public function with($with = [])
    {
        $className = $this->getModel();
        return $className::with($with);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        $className = $this->getModel();
        return $className::query();
    }
}