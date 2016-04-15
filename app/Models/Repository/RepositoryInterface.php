<?php
/**
 * Created by PhpStorm.
 * User: jamesspence
 * Date: 4/14/16
 * Time: 9:36 PM
 */

namespace App\Models\Repository;


use App\Models\AbstractModel;

interface RepositoryInterface
{
    /**
     * Sets the model.
     * @param $model
     */
    public function setModel($model);

    /**
     * Retrieves the class name of the model this repository is meant to represent.
     * @return AbstractModel
     */
    public function getModel();

    /**
     * Finds a model by its ID.
     * @param $id
     * @param array $with
     * @return AbstractModel
     */
    public function find($id, $with = []);

    /**
     * Finds a model, or fails and throws an exception.
     * @param $id
     * @param array $with
     * @return AbstractModel
     */
    public function findOrFail($id, $with = []);

    /**
     * Finds a model, or creates a new one.
     * @param $id
     * @param array $columns
     * @return \Illuminate\Support\Collection|static
     */
    public function findOrNew($id, array $columns = ['*']);

    /**
     * Creates a new model.
     * @param $data
     * @return AbstractModel
     */
    public function create($data);

    /**
     * Finds the first instance, or creates a new model (immediately saving it)
     * @param array $attributes
     * @param array $with
     * @return static
     */
    public function firstOrCreate(array $attributes, $with = []);

    /**
     * Finds the first instance, or creates a new model (without saving it)
     * @param array $attributes
     * @return static
     */
    public function firstOrNew(array $attributes);

    /**
     * Retrieves all records from a database.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = ['*']);

    /**
     * Retrieves the first record based on a where.
     *
     * @param $column
     * @param $operator - (=, >, <, <>, etc)
     * @param $value
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function whereFirst($column, $operator, $value, $with = []);

    /**
     * Retrieves all records based on a where.
     *
     * @param $column
     * @param $operator
     * @param $value
     * @return mixed
     */
    public function whereGet($column, $operator, $value);

    /**
     * Instantiates a new model, and returns it.
     *
     * @param array $attrs
     * @return AbstractModel
     */
    public function newModel(array $attrs = []);

    /**
     * @param array $with
     * @return \Illuminate\Database\Query\Builder
     */
    public function with($with = []);

    /**
     * Creates a query builder instance, and returns it.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query();
}