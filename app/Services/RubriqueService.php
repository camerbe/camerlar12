<?php

namespace App\Services;

use App\IRepository\IRubriqueRepository;

/**
 *
 */
class RubriqueService
{


    /**
     * @param $rubriqueService
     */
    public function __construct(protected IRubriqueRepository $rubriqueRepository)
    {

    }

    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input){
        return $this->rubriqueRepository->create($input);
    }

    /**
     * @param int $id
     * @return mixed
     */
    function delete(int $id){
        return $this->rubriqueRepository->delete($id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    function findById(int $id){
        return $this->rubriqueRepository->findById($id);
    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    function update(array $input, $id){
        return $this->rubriqueRepository->update($input, $id);
    }

    /**
     * @return mixed
     */
    function index(){
        return $this->rubriqueRepository->index();
    }

    /**
     * @return mixed
     */
    function allRubrique(){
        return $this->rubriqueRepository->allRubrique();
    }
}
