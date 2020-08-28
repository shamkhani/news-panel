<?php

namespace App\Repositories;

use App\Models\MariaDBModel as Model;

use App\Exceptions\NotFoundModelBaseOnId;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Abstract Repository class.
 *
 * @author  Mostafa Shamkhani,
 */
abstract class MariaDBRepository implements  MariaDBRepositoryInterface
{
    /**
     * Model instance.
     *
     * @var Model
     */
    protected $model;


    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
    }


    /**
     * Return the class path of repository's model.
     *
     * @return string
     */
    abstract protected function getModelClass();

    /**
     * Create new instance of model.
     */
    protected function makeModel()
    {
        $model = app($this->getModelClass());

        if (!$model instanceof Model) {
            throw new \Exception("Invalid Eloquent model");
        }

        $this->model = $model;
    }

    /**
     * Returns the model.
     *
     * @return Model
     */
    protected function getModel()
    {
        return $this->model;
    }

    /**
     * Returns the model which fetched by id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     * @throws NotFoundModelBaseOnId
     */
    public function getModelById($id)
    {
        try {
            return $this->getModel()->findOrFail($id);
        } catch (\Throwable $exception) {
            throw new NotFoundModelBaseOnId();
        }
    }

    /**
     * Insert new row to database, create its model object and return the inserted model.
     *
     * @param array $data
     * @return Model
     */
    public function create($data) : Model
    {
        return $this->getModel()->create($data);
    }

    /**
     * Update model.
     *
     * @param array $data
     * @return bool
     */
    public function update($data, $id) : Model
    {
        $model = $this->find($id);
        $this->getModel()->fill($data);
        $this->getModel()->save();
       return $model;
    }

    /**
     * Removes entity from database by passing model.
     *
     * @param Model $model
     * @return bool|null
     * @throws \Exception
     */
    public function removeByModel(Model $model)
    {
        return $model->delete();
    }

    /**
     * Removes entity from database by passing id.
     *
     * @param int $id
     * @return bool
     */
    public function removeById($id)
    {
        return ($this->getModel()->destroy($id) > 0);
    }

    /**
     * Removes entity from database by passing constraint array.
     *
     * @param array $constraints
     * @return int
     * @throws \Exception
     */
    public function remove($constraints)
    {
        $query = $this->getModel();

        // loop over all given constraints

        foreach ($constraints as $where) {
            $query = $query->where($where[0], $where[1], $where[2]);
        }
        return $query->delete();
    }

    /**
     * Removes all entity from database.
     * @param
     * @return int
     * @throws \Exception
     */
    public function removeAll()
    {
        return $this->getModel()->whereRaw('1=1')->delete();
    }

    /**
     * @param $constraints
     * @param bool $findOne
     * @param array $relations
     * @param array $columns
     * @param int $limit
     * @return bool|null
     */
    public function find($constraints, $findOne = true, $relations = [], $limit = 10, $columns = [])
    {
        $query = $this->getModel();
        if (!empty($relations)) {
            $query = $query->with($relations);
        }
        foreach ($constraints as $where) {
            $query = $query->where($where[0], $where[1], $where[2]);
        }

        return ($findOne ? $query->first() : $this->getAll($limit, $columns, $query));
    }


    /**
     * Get all rows using pagination.
     *
     * @param null|int $items
     * @param array $columns
     * @param bool $query
     * @return mixed
     */
    public function getAll($items = 10, $columns = [], $query = false)
    {
        if ($query) {
            $modelQuery = $query;
        } else {
            $modelQuery = $this->getModel();
        }
        if ($items > 0) {
            return $modelQuery->paginate(
                $items,
                count($columns) ? $columns : ['*']
            );
        } else {
            return $modelQuery->get(count($columns) ? $columns : ['*']);
        }
    }


    /**
     * Return the count of all records in this model.
     *
     * @return int
     */
    public function countAll()
    {
        return $this->getModel()->count();
    }


    /**
     * @return mixed|string
     */
    public function generateUuid()
    {
        return (string)Str::uuid();
    }

    /**
     * @param $model
     */
    public function putModelInCache($model)
    {
        $cacheKey = $this->getModelClass() . '_' . $model->id;
        Cache::forever($cacheKey, $model);
    }


    /**
     * @param $modelId
     */
    public function removeModelFromCache($modelId)
    {
        $cacheKey = $this->getModelClass() . '_' . $modelId;
        Cache::forget($cacheKey);
    }


    /**
     * Insert new rows to database.
     *
     * @param $data
     */
    public function bulkCreate($data)
    {
        $this->model::insert($data);
    }


}
