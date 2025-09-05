<?php

namespace App\Services;

use App\IRepository\IPubRepository;

class PubService
{

    public function __construct(protected IPubRepository $pubRepository)
    {
    }

    function create(array $input){
        return $this->pubRepository->create($input);
    }
    function delete($id){
        return $this->pubRepository->delete($id);
    }

    function findById($id){
        return $this->pubRepository->findById($id);
    }
    function update(array $input, $id){
        return $this->pubRepository->update($input, $id);
    }
    function index(){
        return $this->pubRepository->index();
    }
    function getCachedPub($dimension){
        return $this->pubRepository->getCachedPub($dimension);
    }
    function getPubDimension(){
        return $this->pubRepository->getPubDimension();
    }
    function getPubType(){
        return $this->pubRepository->getPubType();
    }
}
