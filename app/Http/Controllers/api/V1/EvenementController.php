<?php

namespace App\Http\Controllers\api\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Services\EvenementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\Response;


class EvenementController extends Controller
{
    protected $evenementService;

    /**
     * @param $evenementService
     */
    public function __construct(EvenementService $evenementService)
    {
        $this->evenementService = $evenementService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events=$this->evenementService->index();
        if ($events){
            return response()->json([
                'success'=>true,
                'data'=>$events,
                'message'=>"Liste des évènements"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas d'évènements trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $event=$this->evenementService->create($request->all());
        /** @var TYPE_NAME $image */
        $image=Helper::extractImgSrc($request->affiche);
        if ($event){
            if($image){
                $parsedUrl = parse_url($image);
                $path = $parsedUrl['path'];
                $filePath = str_replace(url('/storage'), 'storage', $path);
                $absolutePath = public_path($filePath);
                if (File::exists($absolutePath)){
                    $img = Image::read($absolutePath);
                    $width = $img->width();
                    $height = $img->height();
                    $event->addMedia(public_path($filePath))
                        ->preservingOriginal()
                        ->withResponsiveImages()
                        ->usingName(strval($event->idevent))
                        ->withCustomProperties([
                            'width' => $width,
                            'height' => $height,
                        ])
                        ->toMediaCollection('evenement');
                }
            }
            return response()->json([
                'success'=>true,
                'data'=>$event,
                'message'=>"évènement ajouté"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion d'évènements"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $event=$this->evenementService->findById($id);
        if ($event){
            return response()->json([
                'success'=>true,
                'data'=>$event,
                'message'=>"évènement trouvé"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"évènement inexistant"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event=$this->evenementService->update($request->all(),$id);
        $image=Helper::extractImgSrc($request->affiche);

        if ($event){
            if ($image){
                $media =$event->getMedia('evenement')->where('name',strval($id))->first();
                if(!is_null($media)) {
                    $media->delete();
                }
                $parsedUrl = parse_url($image);
                dd($parsedUrl);
                $path = $parsedUrl['path'];
                $filePath = str_replace(url('/storage'), 'storage', $path);
                $absolutePath = public_path($filePath);
                if (File::exists($absolutePath)) {
                    $img = Image::read($absolutePath);
                    $width = $img->width();
                    $height = $img->height();
                    $event->addMedia(public_path($filePath))
                        ->preservingOriginal()
                        ->withResponsiveImages()
                        ->usingName(strval($id))
                        ->withCustomProperties([
                            'width' => $width,
                            'height' => $height,
                        ])
                        ->toMediaCollection('evenement');
                }
            }

            return response()->json([
                'success'=>true,
                'data'=>$event,
                'message'=>"évènement mis à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'un évènement"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $deletingPub=$this->evenementService->findById($id);
        $media=$deletingPub->getMedia('evenement')->first();
        $pub=$this->evenementService->delete($id);
        if ($pub){
            if($media){
                $media->delete();
            }
            return response()->json([
                'success'=>true,
                'data'=>$pub,
                'message'=>"évènement supprimé",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'un évènement"
        ],Response::HTTP_NO_CONTENT);
    }
    public function getCachedEvenements()
    {
        $events=$this->evenementService->getCachedEvenements();
        if ($events){
            return response()->json([
                'success'=>true,
                'data'=>$events,
                'message'=>"évènements trouvés"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"évènements inexistants"
        ],Response::HTTP_NOT_FOUND);
    }
}
