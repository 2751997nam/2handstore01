<?php
namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

abstract class EloquentRepository implements RepositoryInterface
{
    protected $model;
    private $where;
    private $orWhere;

    public function __construct()
    {
        $this->app = new App();
        $this->makeModel();
    }
    
    abstract public function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function newQuery()
    {
        $this->model = $this->model->newQuery();
        
        return $this;
    }

    public function where($conditions, $operator = null, $value = null)
    {
        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, '='];
        }

        $this->where[] = [$conditions, $operator, $value];

        return $this;
    }

    public function orWhere($conditions, $operator = null, $value = null)
    {
        if (func_num_args() == 2) {
            list($value, $operator) = [$operator, '='];
        }

        $this->orWhere[] = [$conditions, $operator, $value];

        return $this;
    }

    public function loadWhere()
    {
        if ($this->where && count($this->where)) {
            foreach ($this->where as $where) {
                if (is_array($where[0])) {
                    $this->model->where($where[0]);
                } else {
                    if (count($where) == 3) {
                        $this->model->where($where[0], $where[1], $where[2]);
                    } else {
                        $this->model->where($where[0], '=', $where[1]);
                    }
                }
            }
        }
        if ($this->orWhere && count($this->orWhere)) {
            foreach ($this->orWhere as $orWhere) {
                if (is_array($orWhere[0])) {
                    $this->model->orWhere($orWhere[0]);
                } else {
                    if (count($orWhere) == 3) {
                        $this->model->orWhere($orWhere[0], $orWhere[1], $orWhere[2]);
                    } else {
                        $this->model->orWhere($orWhere[0], '=', $orWhere[1]);
                    }
                }
            }
        }
    }

    public function find($id, $columns = ['*'])
    {
        $this->makeModel();

        return $this->model->find($id, $columns);
    }
    
    public function findOrFail($id, $columns = ['*'])
    {
        $this->makeModel();

        return $this->model->findOrFail($id, $columns);
    }

    public function create(array $data)
    {
        $this->makeModel();

        return $this->model->create($data);
    }
    
    public function update(array $data)
    {
        $this->newQuery()
            ->loadWhere();

        return $this->model->update($data);
    }

    public function delete($id)
    {
        $this->makeModel();

        return $this->model->destroy($id);
    }

    public function get($columns = ['*'])
    {
        $this->newQuery()
            ->loadWhere();

        return $this->model->get($columns);
    }

    public function count()
    {
        $this->newQuery()
            ->loadWhere();

        return $this->model->count();
    }

    public function lists($column, $key = null)
    {
        $this->newQuery()
            ->loadWhere();

        return $this->model->lists($column, $key = null);
    }

    public function paginate($perPage = null, $columns = ['*'])
    {
        $perPage = $perPage === null ? config('database.paginate') : $perPage;
        $this->newQuery()
            ->loadWhere();

        return $this->model->paginate($perPage = config('database.paginate'), $columns = ['*']);
    }

    public function orderBy($key, $value)
    {
        $this->newQuery()
            ->loadWhere();
        
        return $this->model->orderBy($key, $value);
    }

    public function first()
    {
        $this->newQuery()
            ->loadWhere();

        return $this->model->first();
    }

    public function pluck($columns = ['*'])
    {
        $this->newQuery()
            ->loadWhere();

        return $this->model->pluck($columns);
    }

    public function toArray()
    {
        $this->newQuery()
            ->loadWhere();
        
        return $this->model->toArray();
    }

    public function all()
    {
        $this->newQuery()
            ->loadWhere();
        
        return $this->model->all();
    }

    public function with($relationships = [''])
    {
        $this->newQuery()
            ->loadWhere();
        
        return $this->model->with($relationships);
    }
}
