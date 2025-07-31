<?php

namespace App\Repositories;

/**
 *
 */
abstract class BaseRepository
{
    /**
     * @var
     */
    protected $model;

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id){
        return $this->model->findOrFail($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id){
        return $this->model->findOrFail($id)->delete();
    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    public function update(Array $input, $id){

        return $this->model->findOrFail($id)->update($input);
    }

    /**
     * @param array $input
     * @return mixed
     */
    public function create(Array $input){
        return $this->model->create($input);
    }
}
