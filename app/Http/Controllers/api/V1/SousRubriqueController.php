<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SousRubriqueRequest;
use App\Services\SousrubriqueService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SousRubriqueController extends Controller
{
    /**
     * @var SousrubriqueService
     */
    protected $sousrubriqueService;

    /**
     * @param $sousrubriqueService
     */
    public function __construct(SousrubriqueService  $sousrubriqueService)
    {
        $this->sousrubriqueService = $sousrubriqueService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sousrubriques=$this->sousrubriqueService->index();
        if ($sousrubriques){
            return response()->json([
                'success'=>true,
                'data'=>$sousrubriques,
                'message'=>"Liste des sousrubriques"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de sousrubrique trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SousRubriqueRequest $request)
    {
        $sousrubrique=$this->sousrubriqueService->create($request->all());

        if ($sousrubrique){
            return response()->json([
                'success'=>true,
                'data'=>$sousrubrique,
                'message'=>"Sousrubrique ajoutée"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion de Sousrubrique"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $sousrubrique=$this->sousrubriqueService->findById($id);
        if ($sousrubrique){
            return response()->json([
                'success'=>true,
                'data'=>$sousrubrique,
                'message'=>"Sousrubrique trouvée"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Sousrubrique inexistante"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sousrubrique=$this->sousrubriqueService->update($request->all(),$id);
        if ($sousrubrique){
            return response()->json([
                'success'=>true,
                'data'=>$sousrubrique,
                'message'=>"Sousrubrique mise à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'une Sousrubrique"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $sousrubrique=$this->sousrubriqueService->delete($id);
        if ($sousrubrique){
            return response()->json([
                'success'=>true,
                'data'=>$sousrubrique,
                'message'=>"Sousrubrique supprimée",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'une Sousrubrique"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function allRubrique(){
        $sousrubrique=$this->sousrubriqueService->allRubrique();
        if ($sousrubrique){
            return response()->json([
                'success'=>true,
                'data'=>$sousrubrique,
                'message'=>"Liste des Rubrique"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de Rubrique trouvée"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function allSousRubrique(){
        $sousrubrique=$this->sousrubriqueService->allSousRubrique();
        if ($sousrubrique){
            return response()->json([
                'success'=>true,
                'data'=>$sousrubrique,
                'message'=>"Liste des Sousrubrique"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de Sousrubrique trouvée"
        ],Response::HTTP_NOT_FOUND);
    }
}
