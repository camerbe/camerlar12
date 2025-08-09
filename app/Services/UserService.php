<?php

namespace App\Services;

use App\IRepository\IUserRepository;

class UserService
{

    public function __construct(protected IUserRepository $userRepository)
    {
    }
    function create(array $input){
        return $this->userRepository->create($input);
    }
    function delete($id){
        return $this->userRepository->delete($id);
    }
    function findById($id){
        return $this->userRepository->findById($id);
    }
    function update(array $input, $id){
        return $this->userRepository->update($input, $id);
    }
    function index(){
        return $this->userRepository->index();
    }
    function changePassword(array $input, $id){
        return $this->userRepository->changePassword($input, $id);
    }

}
