<?php
namespace App\Repositories\Contracts;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;
/**
 * Class Repository
 * @package Bosnadev\Repositories\Eloquent
 */
abstract class Repository implements RepositoryInterface {

    /**
     * @var App
     */
    private $app;

    /**
     * @var
     */
    protected $model;

    /**
     * @param App $app
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function model();

    /**
     * Fetch all records

     * @param array $columns
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'], $orderByColumn = null, $orderBy = 'ASC')
    {
        $this->makeModel();
        if($orderByColumn) {
            return $this->model->orderBy($orderByColumn, $orderBy)->get($columns);
        } else {
            return $this->model->get($columns);
        }

    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Create record
     * @param array $data
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function create(array $data)
    {
        $this->makeModel();
        return $this->model->create($data);
    }

    /**
     * Create record
     * @param array $data
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function insert(array $data)
    {
        $this->makeModel();
        return $this->model->insert($data);
    }



    /**
     * Update  record

     * @param array $data
     * @param $id
     * @param string $attribute
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function update(array $data, $id, $attribute="id")
    {
        $this->makeModel();
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * Delete  record

     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        // $this->makeModel();
        // return $this->model->destroy($id);
        // return $this->model->delete($id);
        // return $this->model->where($attribute, '=', $id)->delete();
        return $this->find($id)->delete();
    }

    /**
     * Fetch a record details

     * @param $id
     * @param array $columns
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function find($id, $columns = ['*'])
    {
        $this->makeModel();
        return $this->model->find($id, $columns);
    }

    /**
     * Fetch records by criteria

     * @param array $attribute
     * @param string $type
     * @param array $columns
     * @param string $orderByColumn
     * @param string $orderBy
            [Default: ASC]
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function findBy(array $attributes, $type = 'get', $columns = ['*'], $orderByColumn = null, $orderBy = 'ASC', $limit = null, $offset = null )
    {
        if(! empty($attributes)) {
            $this->makeModel();
            $result = $this->model->where($attributes);
            if($orderByColumn) {
                $result = $this->model->where($attributes)->orderBy($orderByColumn, $orderBy);
                //return
            }

            if($offset) {
                $result->offset($offset);
            }

            if($limit) {
                $result->limit($limit);
            }

            if($type == 'count') {
                return $result->count();
            }

            return $result->{$type}($columns);
        }
    }

    /**
     * Find all active records

     * @param array $columns
     * @param string $orderByColumn
     * @param string $orderBy
            [Default: ASC]
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function findActive($columns = ['*'], $orderByColumn = null, $orderBy = 'ASC', $limit = null)
    {
        $this->makeModel();
        $result = $this->model->where('is_active', 1);

        if($orderByColumn && $limit==null) {
            return $this->model->where('is_active', 1)->orderBy($orderByColumn, $orderBy)->get($columns);
        }
        if($orderByColumn && $limit) {
            return $this->model->where('is_active', 1)->orderBy($orderByColumn, $orderBy)->limit($limit)->get($columns);
        }

        return $result->get($columns);
    }

    /**
     * Update records by criteria

     * @param array $data
     * @param array $where
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function updateBy(array $data, array $where)
    {
        if(! empty($data) && ! empty($where)) {
            $this->makeModel();
            return $this->model->where($where)->update($data);
        }
    }

    /**
     * Update or Create record by criteria

     * @param array $data
     * @param array $where
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function updateOrCreate(array $data, array $where)
    {
        if(! empty($data) && ! empty($where)) {
            $this->makeModel();
            return $this->model->updateOrCreate($where, $data);
        }
    }

    /**
     * Find  or Create record by criteria

     * @param array $where
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function findOrCreate(array $where)
    {
        if(! empty($where)) {
            $this->makeModel();
            return $this->model->firstOrCreate($where);
        }
    }

    /**
     * Delete  records by criteria

     * @param array $where
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function deleteBy(array $where)
    {
        if(! empty($where)) {
            $this->makeModel();
            return $this->model->where($where)->delete();
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            // throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
            Log::error('Exception: ' ."Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model->newQuery();
    }


    public function lazy(array $where, $orderByColumn=NULL)
    {
        $this->makeModel();

        if(! empty($where)) {
            $this->makeModel();
            if($orderByColumn) {
                return $this->model->where($where)->orderBy($orderByColumn, 'ASC')->cursor();
            } else {
                return $this->model->where($where)->cursor();
            }

            //return $this->model->where($where)->delete();
        }
    }

    /**
     * truncate table
    */
    public function truncate()
    {
        $this->makeModel();
        return $this->model->truncate();
    }

    /**
     * Fetch records with trashed  by criteria

     * @param array $attribute
     * @param string $type
     * @param array $columns
     * @param string $orderByColumn
     * @param string $orderBy
            [Default: ASC]
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function findWithTrashed(array $attributes, $type = 'get' )
    {
        if(! empty($attributes)) {
            $this->makeModel();
            $result = $this->model->where($attributes)->withTrashed();

            return $result->{$type}();
        }
    }


    /**
     * Find distinct records

     * @param array $columns
     * @param string $orderByColumn
     * @param string $orderBy
            [Default: ASC]
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function distinct($where = [], $columns = ['*'], $orderByColumn = null, $orderBy = 'ASC')
    {

        $this->makeModel();

        if($orderByColumn) {
            return $this->model->distinct()->where($where)->orderBy($orderByColumn, $orderBy)->get($columns);
        } else {
            return $this->model->distinct()->where($where)->get($columns);
        }
    }


    /**
     * Find all  records with lazy collection

     * @param array $columns
     * @param string $orderByColumn
     * @param string $orderBy
            [Default: ASC]
     * @return Illuminate\Database\Eloquent\lazyCollection
     */

    public function lazyLoad(array $where, $columns = ['*'], $orderByColumn = null, $orderBy = 'ASC' )
    {
        if(! empty($where)) {
            $this->makeModel();
           // return $this->model->where($where)->cursor($columns);
            if($orderByColumn) {
                return $this->model->select($columns)->where($where)->orderBy($orderByColumn, $orderBy)->cursor();
            } else {
                return $this->model->select($columns)->where($where)->cursor();
            }

        }
    }

    /**
     * Fetch records by criteria

     * @param array $attribute
     * @param string $type
     * @param array $columns
     * @param string $orderByColumn
     * @param string $orderBy
            [Default: ASC]
     * @return Illuminate\Database\Eloquent\Collection
     */

    public function findIn(array $whereIn, array $where = [], $inColumn, $columns = ['*'])
    {
        if(! empty($whereIn)) {
            $this->makeModel();
            return $this->model->whereIn($inColumn, $whereIn)->where($where)->get($columns);
        }
    }
}
