<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class EloquentRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;
    /**
     * Current Object instance
     *
     * @var object
     */
    protected $instance;

    /**
     * EloquentRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        //other -> new Model
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Get All
     * @return Collection|static[]
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * Find a single record
     *
     * @param array $filter
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findByOrFail(array $filter, array $relations = [])
    {
        $this->instance = $this->model;

        $this->applyFilters($filter);

        $this->instance = $this->instance->with($relations)->firstOrFail();

        return $this->instance;
    }

    /**
     * Find a single record
     *
     * @param array $filter
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findBy(array $filter, array $relations = [])
    {

        $this->instance = $this->model;

        $this->applyFilters($filter);

        $this->instance = $this->instance->with($relations)->first();

        return $this->instance;
    }

    /**
     * Find a single record
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findOrFail($id, array $relations = [])
    {

        $this->instance = $this->model->findOrFail($id)->load($relations);

        return $this->instance;
    }

    /**
     * Find a single record
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function find($id, array $relations = [])
    {
        $this->instance = $this->model->find($id)->load($relations);
        return $this->instance;
    }

    public function findByIds(array $ids, array $relations = []): mixed
    {
        return $this->model->whereIn('id', $ids)->with($relations)->get();
    }

    /**
     * Create
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update
     * @param $id
     * @param array $data
     * @return bool|mixed
     */
    public function update($id, array $data)
    {
        $this->find($id);

        if ($this->instance) {

            $this->instance->update($data);

            return $this->instance;
        }

        return false;
    }

    /**
     * Update an existing model or create a new model if none exists.
     *
     * @param array $conditions Conditions to find the model
     * @param array $data Data to update or create with
     * @return Model The updated or newly created model
     */
    public function updateOrCreate(array $conditions, array $data): Model
    {
        return $this->model->updateOrCreate($conditions, $data);
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $this->find($id);

        if ($this->instance) {

            $this->instance->delete();

            return true;
        }

        return false;
    }

    public function getBy(array $filter, array $relations = [])
    {

        $this->getByQueryBuilder($filter, $relations);

        return $this->instance->get();
    }

    public function getByQueryBuilder(array $filter, array $relations = [], $sort = ['id', 'desc'])
    {

        $this->getQueryBuilderOrderBy(...$sort);

        $this->applyFilters($filter);

        return $this->instance->with($relations);
    }

    public function getQueryBuilderOrderBy($column = 'id', $sort = 'DESC')
    {

        $this->getQueryBuilder();

        $this->instance = $this->instance->orderBy($column, $sort);

        return $this->instance;
    }

    /**
     * get query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQueryBuilder()
    {
        $this->instance = $this->model->newQuery();

        return $this->instance;
    }

    protected function applyFilters(array $filter)
    {

        foreach ($filter as $field => $value) {
            if (is_array($value)) {

                [$field, $condition, $val] = $value;

                $this->instance = match (strtoupper($condition)) {
                    'IN' => $this->instance->whereIn($field, $val),
                    'NOT_IN' => $this->instance->whereNotIn($field, $val),
                    default => $this->instance->where($field, $condition, $val)
                };
            } else {
                $this->instance = $this->instance->where($field, $value);
            }
        }
    }

    public function authorize($action = 'view', $guard = 'web')
    {

        if (!$this->instance || auth()->guard($guard)->user()->can($action, $this->instance)) {

            return true;
        }

        throw new HttpException(401, 'UNAUTHORIZED');
    }

    public function getInstance()
    {

        return $this->instance;
    }

    public function updateAttribute(mixed $id, string $attribute, mixed $value): void
    {
        $modelClass = $this->getModel();
        $model = $modelClass::find($id);
        $model->$attribute = $value;
        $model->save();
    }

    public function updateByCondition(array $conditions, array $data): mixed
    {
        $model = $this->getModel()::where($conditions)->first();

        if ($model) {
            $model->update($data);
            return $model;
        }

        return false;
    }

    /**
     * Find records by a specific field.
     *
     * @param string $field The field to filter by.
     * @param mixed $value The value to search for.
     * @param array $relations Optional related models to load.
     * @return Model|null Returns a collection of found records.
     */
    public function findByField(string $field, $value, array $relations = []): ?Model
    {
        return $this->model->where($field, $value)->with($relations)->first();
    }


}
