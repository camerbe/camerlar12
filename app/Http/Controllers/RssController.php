<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class RssController extends Controller
{
    protected $articleService;
    private $categories;

    /**
     * @param $articleService
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        $this->categories=$this->articleService->getCategories();
    }
    public function feed(){

        $items=ArticleResource::collection($this->articleService->getNewsForRss()->take(20));
        $rss = View::make('rss.feed', compact('items'));
        return response($rss, 200)->header('Content-Type', 'application/xml');
    }


    /**
     * @return ResponseFactory|Response
     */
    public function politique(){

        return $this->generateRssByCategory('POLITIQUE');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function societe(){
        return $this->generateRssByCategory('SOCIETE');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function economie(){
        return $this->generateRssByCategory('ECONOMIE');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function diaspora(){
        return $this->generateRssByCategory('DIASPORA');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function pointdevue(){
        return $this->generateRssByCategory('POINT DE VUE');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function religion(){
        return $this->generateRssByCategory('RéLIGION');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function sante(){
        return $this->generateRssByCategory('SANTE');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function geopolitique(){
        return $this->generateRssByCategory('GéOPOLITIQUE');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function serail(){
        return $this->generateRssByCategory('SéRAIL');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function panafricanisme(){
        return $this->generateRssByCategory('PANAFRICANISME');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function people(){
        return $this->generateRssByCategory('PEOPLE');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function sport(){
        return $this->generateRssByCategory('SPORT');

    }



    /**
     * @param string $sousrubrique
     * @return ResponseFactory|Response
     */
    private function generateRssByCategory(string $sousrubrique){
        $result = collect($this->categories)->first(function ($item) use ($sousrubrique) {
            return $item['sousrubrique'] === $sousrubrique;
        });
        $sousrub=Str::slug($sousrubrique);
        $view="rss.{$sousrub}";
        $id=$result['id'] ?? null;
        $items=$this->articleService->getArticlesByCategory($id);
        $items = collect($items)->take(20);

        $rss = View::make($view, compact('items'));
        return response($rss, 200)->header('Content-Type', 'application/xml');
    }

}
