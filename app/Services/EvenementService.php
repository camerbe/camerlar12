<?php

namespace App\Services;

use App\IRepository\IEvenementRepository;

class EvenementService
{

    public function __construct(protected IEvenementRepository $evenementRepository)
    {
    }
    public function index(){
        return $this->evenementRepository->index();
    }
    public function create(array $input){
        return $this->evenementRepository->create($input);
    }
    public function findById($id){
        return $this->evenementRepository->findById($id);
    }
    public function delete($id){
        return $this->evenementRepository->delete($id);
    }
    public function update(array $input,$id){
        return $this->evenementRepository->update($input,$id);
    }
    public function getCachedEvenements(){
        return $this->evenementRepository->getCachedEvenements();
    }
}
