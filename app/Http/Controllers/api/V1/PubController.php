<?php

namespace App\Http\Controllers\api\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PubRequest;
use App\Services\PubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class PubController extends Controller
{
    protected $pubService;

    /**
     * @param $pubService
     */
    public function __construct(PubService $pubService)
    {
        $this->pubService = $pubService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pubs=$this->pubService->index();
        if ($pubs){
            return response()->json([
                'success'=>true,
                'data'=>$pubs,
                'message'=>"Liste des pubs"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de pubs trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PubRequest $request)
    {

        //dd($absolutePath);
        $pub=$this->pubService->create($request->all());
        $image=Helper::extractImgSrc($request->pub);
        if ($pub){
            if($image){
                $parsedUrl = parse_url($image);
                $path = $parsedUrl['path'];
                $filePath = str_replace(url('/storage'), 'storage', $path);
                $absolutePath = public_path($filePath);
                if (File::exists($absolutePath)){
                    $img = Image::read($absolutePath);
                    $width = $img->width();
                    $height = $img->height();
                    $pub->addMedia(public_path($filePath))
                        ->preservingOriginal()
                        ->withResponsiveImages()
                        ->usingName(strval($pub->idpub))
                        ->withCustomProperties([
                            'width' => $width,
                            'height' => $height,
                        ])
                        ->toMediaCollection('pub');
                }
            }
            return response()->json([
                'success'=>true,
                'data'=>$pub,
                'message'=>"Pub ajouté"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion de Pub"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pub=$this->pubService->findById($id);
        if ($pub){
            return response()->json([
                'success'=>true,
                'data'=>$pub,
                'message'=>"Pub trouvé"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pub inexistant"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $pub=$this->pubService->update($request->all(),$id);
        $image=Helper::extractImgSrc($request->pub);
        if ($pub){
            $media =$pub->getMedia('pub')->where('name',strval($id))->first();
            if(!is_null($media)) {
                $media->delete();
            }
            $parsedUrl = parse_url($image);
            $path = $parsedUrl['path'];
            $filePath = str_replace(url('/storage'), 'storage', $path);
            $absolutePath = public_path($filePath);
            if (File::exists($absolutePath)) {
                $img = Image::read($absolutePath);
                $width = $img->width();
                $height = $img->height();
                $pub->addMedia(public_path($filePath))
                    ->preservingOriginal()
                    ->withResponsiveImages()
                    ->usingName(strval($id))
                    ->withCustomProperties([
                        'width' => $width,
                        'height' => $height,
                    ])
                    ->toMediaCollection('pub');
            }
            return response()->json([
                'success'=>true,
                'data'=>$pub,
                'message'=>"Pub mise à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'une Pub"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $deletingPub=$this->pubService->findById($id);
        $media=$deletingPub->getMedia('pub')->first();
        $pub=$this->pubService->delete($id);
        if ($pub){
            if($media){
                $media->delete();
            }
            return response()->json([
                'success'=>true,
                'data'=>$pub,
                'message'=>"Pub supprimée",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'une Pub"
        ],Response::HTTP_NO_CONTENT);
    }
    public function getCachedPub($dimension){
        $pub=$this->pubService->getCachedPub($dimension);
        if ($pub){
            return response()->json([
                'success'=>true,
                'data'=>$pub,
                'message'=>"Pub trouvé"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pub inexistant"
        ],Response::HTTP_NOT_FOUND);
    }
}
