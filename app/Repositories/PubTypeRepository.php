<?php

namespace App\Repositories;

use App\IRepository\IPubType;
use App\Models\Pubtype;
use Illuminate\Support\Str;

class PubTypeRepository extends Repository implements IPubType
{

    public function __construct(Pubtype $pubtype)
    {
        parent::__construct($pubtype);
    }

    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input)
    {
        $input['pubtype']=Str::upper($input['pubtype']);
        return parent::create($input);
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
        return parent::findById($id);
    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    function update(array $input, $id)
    {
        $currentPupType=$this->findById($id);
        $input['pubtype']=isset($input['pubtype'])? Str::upper($input['pubtype']):$currentPupType->pubtype;
        return parent::update($input, $id);
    }

    /**
     * @return mixed
     */
    function getAll()
    {
        return Pubtype:: orderBy('pubtype', 'asc')->get();
    }

    /**
     * @return mixed
     */



}
