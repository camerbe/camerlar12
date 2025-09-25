<?php

namespace App\Http\Controllers\api\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    protected $articleService;

    /**
     * @param $articleService
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles=$this->articleService->index();
        if ($articles){
            return response()->json([
                'success'=>true,
                'data'=>$articles,
                'message'=>"Liste des articles"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas d'article trouvé"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $article=$this->articleService->create($request->all());
        //dd($article);
        $image=Helper::extractImgSrc($request->image);
        if ($article){
            if($image){
                $parsedUrl = parse_url($image);
                $path = $parsedUrl['path'];
                $filePath = str_replace(url('/storage'), 'storage', $path);
                $absolutePath = public_path($filePath);
                if (File::exists($absolutePath)){
                    $img = Image::read($absolutePath);
                    $width = $img->width();
                    $height = $img->height();
                    $article->addMedia(public_path($filePath))
                        ->preservingOriginal()
                        ->withResponsiveImages()
                        ->usingName(strval($article->slug))
                        ->withCustomProperties([
                            'width' => $width,
                            'height' => $height,
                        ])
                        ->toMediaCollection('article');
                }
            }
            return response()->json([
                'success'=>true,
                'data'=>$article,
                'message'=>"Article ajouté"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Problème survenu lors de l'insertion d'article"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $article=$this->articleService->findById($id);
        if ($article){
            return response()->json([
                'success'=>true,
                'data'=>$article,
                'message'=>"Article trouvé"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Article inexistant"
        ],Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $article=$this->articleService->update($request->all(),$id);
        $image=Helper::extractImgSrc($request->image);

        if ($article){
            if ($image){
                $media =$article->getMedia('article')->where('name',$article->slug)->first();
                if(!is_null($media)) {
                    $media->delete();
                }
                $parsedUrl = parse_url($image);
                //dd($parsedUrl);
                $path = $parsedUrl['path'];
                $filePath = str_replace(url('/storage'), 'storage', $path);
                $absolutePath = public_path($filePath);
                if (File::exists($absolutePath)) {
                    $img = Image::read($absolutePath);
                    $width = $img->width();
                    $height = $img->height();
                    $article->addMedia(public_path($filePath))
                        ->preservingOriginal()
                        ->withResponsiveImages()
                        ->usingName(strval($id))
                        ->withCustomProperties([
                            'width' => $width,
                            'height' => $height,
                        ])
                        ->toMediaCollection('article');
                }
            }

            return response()->json([
                'success'=>true,
                'data'=>$article,
                'message'=>"Article mis à jour",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la mise à jour d'un article"
        ],Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $deletingarticle=$this->articleService->findById($id);
        $media=$deletingarticle->getMedia('article')->first();
        $pub=$this->articleService->delete($id);
        if ($pub){
            if($media){
                $media->delete();
            }
            return response()->json([
                'success'=>true,
                'data'=>$pub,
                'message'=>"Article supprimé",
            ],Response::HTTP_CREATED);
        }
        return response()->json([
            "sucess"=>false,
            "message"=>"Erreur lors de la suppression d'un article"
        ],Response::HTTP_NO_CONTENT);
    }
    public function getArticleBySlug(string $slug)
    {
        $article=$this->articleService->getArticleBySlug($slug);
        if ($article){
            return response()->json([
                'success'=>true,
                'data'=>$article,
                'message'=>"Article trouvé"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($article)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Article inexistant"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getArticleByUser($user){
        $article=$this->articleService->getArticleByUser($user);
        if ($article){
            return response()->json([
                'success'=>true,
                'data'=>$article,
                'message'=>"Article trouvé"
            ],Response::HTTP_OK);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Article inexistant"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getArticles(){
        $articles=$this->articleService->getArticles();
        if ($articles){
            return response()->json([
                'success'=>true,
                'data'=>$articles,
                'message'=>"Articles trouvés"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($articles)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Articles inexistants"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getTopNews($period){
        $articles=$this->articleService->getTopNews($period);
        if ($articles){
            return response()->json([
                'success'=>true,
                'data'=>$articles,
                'message'=>"Articles trouvés"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($articles)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Articles inexistants"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getSameRubrique($fksousrubrique){
        $articles=$this->articleService->getSameRubrique($fksousrubrique);
        if ($articles){
            return response()->json([
                'success'=>true,
                'data'=>$articles,
                'message'=>"Articles trouvés"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($articles)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Articles inexistants"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getMostReadRubriqueByCountry($fksousrubrique,$fkpays){
        $articles=$this->articleService->getMostReadRubriqueByCountry($fksousrubrique,$fkpays);
        if ($articles){
            return response()->json([
                'success'=>true,
                'data'=>$articles,
                'message'=>"Articles trouvés"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($articles)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Articles inexistants"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getMostReaded(){
        $articles=$this->articleService->getMostReaded();
        if ($articles){
            return response()->json([
                'success'=>true,
                'data'=>$articles,
                'message'=>"Articles trouvés"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($articles)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Articles inexistants"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getNewsByAuthor($author){
        $articles=$this->articleService->getNewsByAuthor($author);
        if ($articles){
            return response()->json([
                'success'=>true,
                'data'=>$articles,
                'message'=>"Articles trouvés"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($articles)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Articles inexistants"
        ],Response::HTTP_NOT_FOUND);
    }
    public function getNewsForRss()
    {
        $articles=$this->articleService->getNewsForRss();
        if ($articles){
            return response()->json([
                'success'=>true,
                'data'=>$articles,
                'message'=>"Liste des articles"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($articles)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas d'article trouvé"
        ],Response::HTTP_NOT_FOUND);
    }
    public function allCountries()
    {
        $countries=$this->articleService->allCountries();
        if ($countries){
            return response()->json([
                'success'=>true,
                'data'=>$countries,
                'message'=>"Liste des pays"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($countries)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de pays trouvé"
        ],Response::HTTP_NOT_FOUND);
    }
    public function allRubrique()
    {
        $rubriques=$this->articleService->allRubrique();
        if ($rubriques){
            return response()->json([
                'success'=>true,
                'data'=>$rubriques,
                'message'=>"Liste des rubriques"
            ],Response::HTTP_OK)
                ->withHeaders([
                    'Cache-Control' => 'public, max-age=3600',
                    'Content-Type' => 'application/json; charset=utf-8',
                    /*'Content-Encoding' => 'gzip',*/
                    'Vary' => 'Accept-Encoding',
                    'X-Content-Type-Options' => 'nosniff',
                    'X-Frame-Options' => 'DENY',
                    'X-XSS-Protection' => '1; mode=block',
                    'ETag' =>  md5(json_encode($rubriques)),
                    'X-Response-Time' => now(),
                ]);
        }
        return response()->json([
            "success"=>false,
            "message"=>"Pas de rubrique trouvé"
        ],Response::HTTP_NOT_FOUND);
    }
}
