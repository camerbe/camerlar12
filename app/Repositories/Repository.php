<?php

namespace App\Repositories;

use App\IRepository\IRepository;

/**
 *
 */
class Repository implements IRepository
{
    /**
     * @var
     */
    protected $model;

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }


    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input)
    {
        return $this->model->create($input);
    }

    /**
     * @param $id
     * @return mixed
     */
    function delete($id)
    {
        $model=$this->model->findOrFail($id);
        return $model->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    function update(array $input, $id)
    {
        $model=$this->model->findOrFail($id);
        $model->update($input);
        return $model;
    }


    /**
     * @return mixed
     */
    function index()
    {
        return $this->model->all();
    }
}
