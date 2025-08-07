<?php

namespace App\Services;

use App\IRepository\IPaysRepository;
use App\Models\Pays;

class PaysService
{

    public function __construct(protected IPaysRepository $paysRepository)
    {
    }
    function create(array $input){
        return $this->paysRepository->create($input);
    }
    function delete($id){
        return $this->paysRepository->delete($id);
    }
    function findById($id)
    {
        return $this->paysRepository->findById($id);
    }
    function update(array $input, $id)
    {
        return $this->paysRepository->update($input, $id);
    }
    function index()
    {
        return $this->paysRepository->index();
    }
    function allPays(){
        return $this->paysRepository->allPays();
    }
    function articleNonCameroon(){
        return $this->paysRepository->articleNonCameroon();
    }
    function articleCameroon(){
        return $this->paysRepository->articleCameroon();
    }
}
