<?php

namespace App\Repositories;

use App\Models\Pubdimension;

class PubDimensionRepository extends Repository
{

    public function __construct(PubDimension $pubDimension)
    {
        parent::__construct($pubDimension);
    }

    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input)
    {
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
        return parent::update($input, $id);
    }

    /**
     * @return mixed
     */
    function index()
    {
        return Pubdimension::orderBy('dimension')->get();
    }

}
