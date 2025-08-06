<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RubriqueRequest;
use App\Services\RubriqueService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RubriqueController extends Controller
{
    protected $rubriqueService;

    /**
     * @param $rubriqueService
     */
    public function __construct(RubriqueService $rubriqueService)
    {
        $this->rubriqueService = $rubriqueService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rubriques=$this->rubriqueService->index();
        if ($rubriques){
            return response()->json([
                'success'=>true,
                'data'=>$rubriques,
                'message'=>"Liste des rubriques"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de rubrique trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RubriqueRequest $request)
    {
        $rubrique=$this->rubriqueService->create($request->all());

        if ($rubrique){
            return response()->json([
                'success'=>true,
                'data'=>$rubrique,
                'message'=>"Rubrique ajouté"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion de Rubrique"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $rubrique=$this->rubriqueService->findById($id);
        if ($rubrique){
            return response()->json([
                'success'=>true,
                'data'=>$rubrique,
                'message'=>"Rubrique trouvée"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Rubrique inexistante"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $rubrique=$this->rubriqueService->update($request->all(),$id);
        if ($rubrique){
            return response()->json([
                'success'=>true,
                'data'=>$rubrique,
                'message'=>"Rubrique mise à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'une Rubrique"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rubrique=$this->rubriqueService->delete($id);
        if ($rubrique){
            return response()->json([
                'success'=>true,
                'data'=>$rubrique,
                'message'=>"Rubrique supprimée",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'une rubrique"
        ],Response::HTTP_NO_CONTENT);
    }
    public function allRubrique()
    {
        $rubriques=$this->rubriqueService->allRubrique();
        if ($rubriques){
            return response()->json([
                'success'=>true,
                'data'=>$rubriques,
                'message'=>"Liste des rubriques"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de rubrique trouvé"
        ],Response::HTTP_NOT_FOUND);
    }
}
