<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{

    public function findByField(string $field, $value, array $relations = []): ?Model;

    /**
     * Return all records
     *
     * @return mixed
     */
    public function getAll();
    /**
     * Find a single record
     *
     * @param array $filter
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findByOrFail(array $filter, array $relations = []);
    /**
     * Find a single record
     *
     * @param array $filter
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findBy(array $filter, array $relations = []);
    /**
     * Find a single record
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function findOrFail($id, array $relations = []);
    /**
     * Find a single record
     *
     * @param int $id
     * @param array $relations
     * @return mixed
     * @throws \Exception
     */
    public function find($id, array $relations = []);

    /**
     * Find multiple records by their IDs.
     *
     * @param array $ids Array of IDs
     * @param array $relations Optional relations to load
     * @return mixed
     */
    public function findByIds(array $ids, array $relations = []): mixed;

    /**
     * Create a new record
     *
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */

    public function create(array $data);

    /**
     * Update the model instance
     *
     * @param int $id The model id
     * @param array $data The input data
     * @return object model instance
     * @throws \Exception
     */
    public function update($id, array $data);


    /**
     * Update an existing model or create a new model if none exists.
     *
     * @param array $conditions Conditions to find the model
     * @param array $data Data to update or create with
     * @return Model The updated or newly created model
     */
    public function updateOrCreate(array $conditions, array $data): Model;

    /**
     * Delete a record
     *
     * @param int $id Model id
     * @return object model instance
     * @throws \Exception
     */

    public function delete($id);
    /**
     * Return all records
     *
     * @param array $relations
     * @param array $filter
     * @return mixed
     */
    public function getBy(array $filter, array $relations = []);
    /**
     * make query
     *
     * @return mixed
     */
    public function getByQueryBuilder(array $filter, array $relations = []);
    /**
     * make query
     *
     * @return mixed
     */
    public function getQueryBuilderOrderBy();
    /**
     * make query
     *
     * @return mixed
     */
    public function getQueryBuilder();
    /**
     * policy
     *
     * @param string $action
     * @param string $guard
     *
     * @return boolean
     */
    public function authorize($action = 'view', $guard = 'web');
    /**
     *
     * @return mixed
     */
    public function getInstance();
    public function updateAttribute(mixed $id, string $attribute, mixed $value);


}
