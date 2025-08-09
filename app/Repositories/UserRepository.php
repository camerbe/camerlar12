<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\IRepository\IUserRepository;
use App\Models\User;
use Illuminate\Support\Str;

class UserRepository extends Repository implements IUserRepository
{
    /**
     * @param $model
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input)
    {
        $input['nom']=Str::upper($input['nom']);
        $input['prenom']=Str::title($input['prenom']);
        $password=$input['password'] ?? '123456';
        $input['password']=bcrypt($password);
        return new UserResource(parent::create($input)) ;
    }

    /**
     * @param $id
     * @return mixed
     */
    function delete($id)
    {
        return parent::delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    function findById($id)
    {
        return  new UserResource(parent::findById($id))  ;
    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    function update(array $input, $id)
    {
        $current=$this->findById($id);
        $input['nom']=isset($input['nom'])? Str::upper($input['nom']):$current->nom;
        $input['prenom']=isset($input['prenom'])? Str::title($input['prenom']):$current->prenom;
        $input['email']= $input['email'] ?? $current->email;
        $input['role']= $input['role'] ?? $current->role;
        return new UserResource(parent::update($input, $id));
    }

    /**
     * @return mixed
     */
    function index()
    {
       return UserResource::collection(
           User::orderBy('nom')
               ->orderBy('prenom')
               ->get()
       );
    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    function changePassword(array $input, $id)
    {
        $current=$this->findById($id);
        $current->password_changed_at=now();
        $current->password=bcrypt($input['password']);
        $current->save();
        return new UserResource($current);
    }
}
