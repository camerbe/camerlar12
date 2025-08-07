<?php

namespace App\Services;

use App\IRepository\ISousrubriqueRepository;

/**
 *
 */
class SousrubriqueService
{

    /**
     * @param ISousrubriqueRepository $sousrubriqueRepository
     */
    public function __construct(protected ISousrubriqueRepository $sousrubriqueRepository)
    {
    }

    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input){
        return $this->sousrubriqueRepository->create($input);
    }

    /**
     * @param $id
     * @return mixed
     */
    function delete($id){
        return $this->sousrubriqueRepository->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    function findById($id){
        return $this->sousrubriqueRepository->findById($id);
    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    function update(array $input, $id){
        return $this->sousrubriqueRepository->update($input, $id);
    }

    /**
     * @return mixed
     */
    function index(){
        return $this->sousrubriqueRepository->index();
    }

    /**
     * @return mixed
     */
    function allRubrique(): mixed
    {
        return $this->sousrubriqueRepository->allRubrique();
    }

    /**
     * @return mixed
     */
    function allSousRubrique(): mixed
    {
        return $this->sousrubriqueRepository->allSousRubrique();
    }
}
