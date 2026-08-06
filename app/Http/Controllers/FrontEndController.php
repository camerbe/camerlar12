<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\V1\ArticleController;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Services\RubriqueRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FrontEndController extends Controller
{
    //
    protected $api;
    private $debat;
    /**
     * @param $articleService
     */
    public function __construct(ArticleController $api )
    {
        $this->api = $api;
        /*-----------------------------------------------------------------*/
        $this->debat= Cache::remember('articles_debat_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(27,25);   // API call
            $array = json_decode($data->getContent(), true);
            return $array['data']; // Store as collection
        });
    }
    public function laUne(){
        //$data = $this->api->getArticles();   // API call
        $heroArticle=$this->api->laUne();
        $array = json_decode($heroArticle->getContent(), true);
        $heroArticle=$array['data'];
        $debat=$this->debat;

        return view('home', compact('heroArticle','debat'));
    }

    public function display(string $rubrique,string $sousrubrique,$slug){
        $plusLus=$sameRubrique=$oneArticle=null;
        $oneArticle =new ArticleResource($this->api->getArticleBySlug($slug));
        $array = json_decode($oneArticle->getContent(), true);
        $oneArticle =  $array['data'];
        $debat=$this->debat;

        if($oneArticle){
            $cacheKey=(string)$oneArticle['fksousrubrique']. ' '.(string)($oneArticle['fkpays']);
            $cacheKey=md5($cacheKey);
            $fksousrubrique=$oneArticle['fksousrubrique'];
            $fkpays=$oneArticle['fkpays'];
            $plusLus=Cache::remember($cacheKey,now()->addHours(12),function()use($fksousrubrique,$fkpays){
                $data=$this->api->getMostReadRubriqueByCountry($fksousrubrique,$fkpays);
                $array = json_decode($data->getContent(), true);
                return $array['data'];
            });

            $cacheKey=md5((string)$fksousrubrique);
            $sameRubrique=Cache::remember($cacheKey,now()->addMinute(15),function()use($fksousrubrique){
                $data=$this->api->getSameRubrique($fksousrubrique);
                $array = json_decode($data->getContent(), true);
                return  $array['data'];
            });


            //dd($sameRubrique);
        }

        return  view('article',
            compact('slug', 'oneArticle','plusLus','sameRubrique','debat'));
    }

    public function getArticlesByRubrique(Request $request){

        $fksousrubrique = RubriqueRegistry::idFor($request->sousrubrique);
        $fkrubrique = RubriqueRegistry::idFor($request->rubrique);
        $cacheKey=md5($request->sousrubrique.$request->sousrubrique);
        $rubriqueArticles=Cache::remember($cacheKey,now()->addMinute(15),function() use($fkrubrique,$fksousrubrique){
            $data= $this->api->getRubriqueArticles($fksousrubrique,$fkrubrique);
            $array = json_decode($data->getContent(), true);
            return $array['data'];
        });

        $heroArticle=$rubriqueArticles[0];

        return view('index2',[
            'rubriqueArticles'=>$rubriqueArticles,
            'heroArticle'=>$heroArticle,
        ]);
    }

}
