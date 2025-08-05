<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PubDimensionRequest;
use App\Services\PubDimensionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PubDimensionController extends Controller
{
    protected $pubDimensionService;

    /**
     * @param $pubDimensionService
     */
    public function __construct(PubDimensionService  $pubDimensionService)
    {
        $this->pubDimensionService = $pubDimensionService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $pubdimensions=$this->pubDimensionService->index();
        if ($pubdimensions){
            return response()->json([
                'success'=>true,
                'data'=>$pubdimensions,
                'message'=>"Liste des pubdimensions"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de pubdimensions trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PubDimensionRequest $request)
    {
        $pubdimension=$this->pubDimensionService->create($request->all());

        if ($pubdimension){
            return response()->json([
                'success'=>true,
                'data'=>$pubdimension,
                'message'=>"Pubdimension ajouté"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion de Pubdimension"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pubdimension=$this->pubDimensionService->findById($id);
        if ($pubdimension){
            return response()->json([
                'success'=>true,
                'data'=>$pubdimension,
                'message'=>"Pubdimension trouvé"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pubdimension inexistant"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pubdimension=$this->pubDimensionService->update($request->all(),$id);
        if ($pubdimension){
            return response()->json([
                'success'=>true,
                'data'=>$pubdimension,
                'message'=>"Pubdimension mis à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'un Pubdimension"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $pubdimension=$this->pubDimensionService->delete($id);
        if ($pubdimension){
            return response()->json([
                'success'=>true,
                'data'=>$pubdimension,
                'message'=>"Pubdimension supprimé",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'un Pubdimension"
        ],Response::HTTP_NO_CONTENT);
    }
}
