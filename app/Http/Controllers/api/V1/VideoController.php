<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{
    protected $videoService;

    /**
     * @param $videoService
     */
    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $videos=$this->videoService->index();
        if ($videos){
            return response()->json([
                'success'=>true,
                'data'=>$videos,
                'message'=>"Liste des vidéos"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de vidéo trouvée"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VideoRequest $request)
    {
        //
        $video=$this->videoService->create($request->all());
        if ($video){
            return response()->json([
                'success'=>true,
                'data'=>$video,
                'message'=>"Vidéo ajoutée"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion de vidéo"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $video=$this->videoService->findById($id);
        if ($video){
            return response()->json([
                'success'=>true,
                'data'=>$video,
                'message'=>"Vidéo trouvée"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Vidéo inexistante"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
        //dd($request->all());
        $video=$this->videoService->update($request->all(),$id);
        if ($video){
            return response()->json([
                'success'=>true,
                'data'=>$video,
                'message'=>"Vidéo mise à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'une vidéo"
        ],Response::HTTP_NO_CONTENT);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
        $video=$this->videoService->delete($id);
        if ($video){
            return response()->json([
                'success'=>true,
                'data'=>$video,
                'message'=>"Vidéo supprimée",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'une vidéo"
        ],Response::HTTP_NO_CONTENT);
    }
    public function getVideoWeek(){
        $videos=$this->videoService->getVideoWeek();
        if ($videos){
            return response()->json([
                'success'=>true,
                'data'=>$videos,
                'message'=>"Vidéos de la semaine",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Vidéos de la semaine inexistante"
        ],Response::HTTP_NO_CONTENT);
    }

    public function getRandomVideo(){
        $videos=$this->videoService->getRandomVideo();
        if ($videos){
            return response()->json([
                'success'=>true,
                'data'=>$videos,
                'message'=>"Vidéos aléatoire",
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Vidéos aléatoire inexistante"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getCamerVideo(){
        $videos=$this->videoService->getCamerVideo();
        if ($videos){
            return response()->json([
                'success'=>true,
                'data'=>$videos,
                'message'=>"Vidéos camer",
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Vidéos camer inexistante"
        ],Response::HTTP_NOT_FOUND);
    }
    public function findAll($camer='Camer')
    {
        //
        $videos=$this->videoService->findAll($camer);
        if ($videos){
            return response()->json([
                'success'=>true,
                'data'=>$videos,
                'message'=>"Liste des vidéos"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Content-Language' => 'fr',
                    'X-Robots-Tag' => 'index, follow',
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($videos)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de vidéo trouvée"
        ],Response::HTTP_NOT_FOUND);
    }
}
