<?php

namespace App\Repositories;

use App\IRepository\ISousrubriqueRepository;
use App\Models\Rubrique;
use App\Models\Sousrubrique;

class SousRubriqueRepository extends Repository implements ISousrubriqueRepository
{
    /**
     * @param $model
     */
    public function __construct(Sousrubrique $sousrubrique)
    {
        parent::__construct($sousrubrique);
    }

    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input)
    {
        $input['sousrubrique']= strtoupper($input['sousrubrique']);
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
        $current=$this->findById($id);
        $input['sousrubrique']=isset($input['sousrubrique'])? strtoupper($input['sousrubrique'])
            :$current->sousrubrique;
        $input['fkrubrique']= $input['fkrubrique'] ?? $current->fkrubrique;
        return parent::update($input, $id);
    }

    /**
     * @return mixed
     */
    function index()
    {
        return Sousrubrique::with('rubrique')->orderBy('sousrubriques.sousrubrique','asc')
            ->join('rubriques','sousrubriques.fkrubrique','=','rubriques.idrubrique')
            ->select('*')->get();
    }

    /**
     * @return mixed
     */
    function allRubrique()
    {
        return Rubrique::orderBy('rubrique','asc')->get();
    }

    /**
     * @return mixed
     */
    function allSousRubrique()
    {
        return $this->index();
    }
}
