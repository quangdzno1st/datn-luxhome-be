<?php


namespace App\Repositories\Base;


interface BaseInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param array $where
     * @param array $orderBy
     * @return mixed
     */
    public function first(array $where, array $orderBy);

    /**
     * @param array $where
     * @param array $orderBy
     * @return mixed
     */
    public function get(array $where, array $orderBy);

    /**
     * @param array $where
     * @return mixed
     */
    public function count(array $where);

    /**
     * @param array $where
     * @param $field
     * @return mixed
     */
    public function sum(array $where, $field);

    /**
     * @param array $where
     * @param $field
     * @param $value
     * @param bool $unique
     * @return mixed
     */
    public function push(array $where, $field, $value, $unique = true);

    /**
     * @param array $where
     * @param $field
     * @param $value
     * @return mixed
     */
    public function pull(array $where, $field, $value);

    /**
     * @return mixed
     */
    public function all();
    /**
     * @param $column
     * @param null $key
     * @return mixed
     */
    public function pluck($column, $key = null);

    /**
     * @param array $where
     * @param $column
     * @param null $key
     * @return mixed
     */
    public function pluckWhere(array $where, $column, $key = null);

    /**
     * @param array $where
     * @param $column
     * @param int $value
     * @return mixed
     */
    public function increment(array $where, $column, $value = 1);

    /**
     * @param array $where
     * @param $column
     * @param int $value
     * @return mixed
     */
    public function decrement(array $where, $column, $value = 1);

    /**
     * @param $data
     * @return mixed
     */
    public function create($data);

    /**
     * @param $data
     */
    public function createMany($data);

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    public function edit($model, $data);

    /**
     * @param array $where
     * @param array $data
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function editWhere(array $where, array $data, $limit = 0, $offset = 0);

    /**
     * @param $model
     * @return mixed
     */
    public function delete($model);

    /**
     * @param $data
     * @param $unique_column
     * @param $update_column
     * @return mixed
     */
    public function upsert($data, $unique_column, $update_column);

    /**
     * @param $data
     * @param $data_update
     * @return mixed
     */
    public function updateOrCreate($data, $data_update);

    /**
     * @param array $where
     * @param array $orderBy
     * @param array $select
     * @param array $with
     * @param $limit
     * @return mixed
     */
    public function paginate(array $where, array $orderBy, array $select, array $with, $limit);

    /**
     * @param array $pipeline
     * @return mixed
     */
    public function aggregate(array $pipeline);

    /**
     * @param array $conditions
     * @return mixed
     */
    public function match(array $conditions);

    /**
     * @param array $select
     * @return mixed
     */
    public function project(array $select);

    /**
     * @param array $sort
     * @return mixed
     */
    public function sort(array $sort);

}
