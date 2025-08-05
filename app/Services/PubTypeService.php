<?php

namespace App\Services;

use App\IRepository\IPubType;
use App\IRepository\IRepository;

class PubTypeService
{

    public function __construct(protected IPubType $pubtypeRepository)
    {

    }
    public function create($data){
        return $this->pubtypeRepository->create($data);
    }
    public function update($data,$id){
        return $this->pubtypeRepository->update($data,$id);
    }
    public function findById($id){
        return $this->pubtypeRepository->findById($id);
    }
    public function delete($id){
        return $this->pubtypeRepository->delete($id);
    }
    public function getAll(){
        return $this->pubtypeRepository->getAll();
    }

}
