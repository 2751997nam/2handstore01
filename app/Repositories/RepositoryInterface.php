<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function where($conditions, $operator = null, $value = null);

    public function orWhere($conditions, $operator = null, $value = null);

    public function count();
    
    public function get($columns = ['*']);

    public function lists($column, $key = null);

    public function paginate($perPage = 20, $columns = ['*']);

    public function find($id, $columns = ['*']);

    public function findOrFail($id, $columns = ['*']);

    public function create(array $data);

    public function update(array $data);

    public function delete($id);

    public function orderBy($key, $value);

    public function first();

    public function pluck($columns = ['*']);

    public function toArray();

    public function with($relationships = ['']);
}
