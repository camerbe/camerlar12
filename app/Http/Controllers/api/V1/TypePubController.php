<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PubTypeRequest;
use App\Services\PubTypeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TypePubController extends Controller
{
    protected $pubtypeService;

    /**
     * @param $pubtypeService
     */
    public function __construct(PubTypeService $pubtypeService)
    {
        $this->pubtypeService = $pubtypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pubtypes=$this->pubtypeService->getAll();
        if ($pubtypes){
            return response()->json([
                'success'=>true,
                'data'=>$pubtypes,
                'message'=>"Liste des pubtypes"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de pubtypes trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PubTypeRequest $request)
    {
        //

        $pubtype=$this->pubtypeService->create($request->all());

        if ($pubtype){
            return response()->json([
                'success'=>true,
                'data'=>$pubtype,
                'message'=>"Pubtype ajouté"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion de Pubtype"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pubtype=$this->pubtypeService->findById($id);
        if ($pubtype){
            return response()->json([
                'success'=>true,
                'data'=>$pubtype,
                'message'=>"Pubtype trouvé"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pubtype inexistant"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $pubtype=$this->pubtypeService->update($request->all(),$id);
        if ($pubtype){
            return response()->json([
                'success'=>true,
                'data'=>$pubtype,
                'message'=>"Pubtype mis à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'un Pubtype"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $pubtype=$this->pubtypeService->delete($id);
        if ($pubtype){
            return response()->json([
                'success'=>true,
                'data'=>$pubtype,
                'message'=>"Pubtype supprimé",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'un Pubtype"
        ],Response::HTTP_NO_CONTENT);
    }
}
