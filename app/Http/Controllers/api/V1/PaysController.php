<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaysRequest;
use App\Services\PaysService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaysController extends Controller
{
    protected $paysService;

    /**
     * @param $paysService
     */
    public function __construct(PaysService  $paysService)
    {
        $this->paysService = $paysService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries=$this->paysService->index();
        if ($countries){
            return response()->json([
                'success'=>true,
                'data'=>$countries,
                'message'=>"Liste des pays"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de pays trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaysRequest $request)
    {
        $country=$this->paysService->create($request->all());

        if ($country){
            return response()->json([
                'success'=>true,
                'data'=>$country,
                'message'=>"Pays ajouté"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion de Pays"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $country=$this->paysService->findById($id);
        if ($country){
            return response()->json([
                'success'=>true,
                'data'=>$country,
                'message'=>"Pays trouvé"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Paysinexistant"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $country=$this->paysService->update($request->all(),$id);
        if ($country){
            return response()->json([
                'success'=>true,
                'data'=>$country,
                'message'=>"Pays mis à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'un Pays"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $country=$this->paysService->delete($id);
        if ($country){
            return response()->json([
                'success'=>true,
                'data'=>$country,
                'message'=>"Pays supprimé",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'un Pays"
        ],Response::HTTP_NO_CONTENT);
    }
    public function allPays()
    {
        $countries=$this->paysService->allPays();
        if ($countries){
            return response()->json([
                'success'=>true,
                'data'=>$countries,
                'message'=>"Liste des pays"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de pays trouvé"
        ],Response::HTTP_NOT_FOUND);
    }
    public function articleCameroon()
    {
        $countries=$this->paysService->articleCameroon();
        if ($countries){
            return response()->json([
                'success'=>true,
                'data'=>$countries,
                'message'=>"Liste des pays"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de pays trouvé"
        ],Response::HTTP_NOT_FOUND);
    }
    public function articleNonCameroon()
    {
        $countries=$this->paysService->articleNonCameroon();
        if ($countries){
            return response()->json([
                'success'=>true,
                'data'=>$countries,
                'message'=>"Liste des pays"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de pays trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

}
