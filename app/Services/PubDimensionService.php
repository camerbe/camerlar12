<?php

namespace App\Services;

use App\IRepository\IRepository;

class PubDimensionService
{

    public function __construct(protected IRepository $pubDimensionRepository)
    {
    }

    public function index(){
        return $this->pubDimensionRepository->index();
    }
    public function create($data){
       return $this->pubDimensionRepository->create($data) ;
    }
    public function update($data,$id){
        return $this->pubDimensionRepository->update($data,$id);
    }
    public function findById($id){
        return $this->pubDimensionRepository->findById($id);
    }
    public function delete($id){
        return $this->pubDimensionRepository->delete($id);
    }
}
