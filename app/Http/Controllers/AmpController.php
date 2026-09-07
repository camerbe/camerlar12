<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\V1\EvenementController;
use App\Http\Controllers\api\V1\PubController;
use App\Http\Controllers\api\V1\VideoController;
use App\Services\SchemaOrg\CollectionPageSchema;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\api\V1\ArticleController;
use Nette\Utils\Random;
use Symfony\Component\HttpFoundation\Response;

class AmpController extends Controller
{
    //
    protected $api;
    protected $event;
    protected $video;
    protected $pub;


    private $debat;
    private $droit;
    private $evt;
    private $camer;
    private $sopie;
    private $publicite;
    private $archives;
    private $camerVideos;
    private $sopieVideos;
    private $collectionPageSchema;

    public function __construct(
        ArticleController $api,
        EvenementController $event,
        VideoController $video,
        PubController $pub,
        CollectionPageSchema $collectionPageSchema
    ){
        $this->api=$api;
        $this->event=$event;
        $this->video=$video;
        $this->pub=$pub;
        $this->collectionPageSchema=$collectionPageSchema;


        //------------------ event
        $data=$this->event->getCachedEvenements();
        $array = json_decode($data->getContent(), true);
        $this->evt = $array['data'];
        //------------------ Video Camer
        $data=$this->video->getOneVideo('Camer');
        $array = json_decode($data->getContent(), true);
        $this->camer = $array['data'];
        //------------------ Video Camer
        $data=$this->video->getOneVideo('Sopie');
        $array = json_decode($data->getContent(), true);
        $this->sopie = $array['data'];
        //------------------ Pub
        $data=$this->pub->getCachedPub(300);
        $array = json_decode($data->getContent(), true);
        $this->publicite = $array['data'] ?? [];
        //dd($pub);
        $this->debat= Cache::remember('articles_debat_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(27,25);   // API call
            $array = json_decode($data->getContent(), true);
            return $array['data']; // Store as collection
        });
        $this->droit= Cache::remember('articles_droit_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(33,30);   // API call
            $array = json_decode($data->getContent(), true);
            return $array['data']; // Store as collection
        });
        /*$droit= Cache::remember('articles_droit_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(33,30);   // API call
            $array = json_decode($data->getContent(), true);
            return collect($array['data']); // Store as collection
        });*/
        //Cache::forget($cacheKey);
        //Cache::forget('archive');
        $this->archives = Cache::remember('archive', now()->addHours(12), function () {

            $data1 = $this->api->getTopNews('week');
            $arrayweek = json_decode($data1->getContent(), true);

            $data2 = $this->api->getTopNews('month');
            $arraymonth = json_decode($data2->getContent(), true);

            $data3 = $this->api->getTopNews('year');
            $arrayyear = json_decode($data3->getContent(), true);
            //dd($arrayyear);
            return [
                'week' => $arrayweek?? [],
                'month' => $arraymonth ?? [],
                'year' => $arrayyear ?? [],
            ] ;
        });


        $this->camerVideos= Cache::remember('videoCamer', now()->addMinute(15), function () {
            $data = $this->video->findAll();  // API call
            $array = json_decode($data->getContent(), true);
            return $array['data']; // Store as collection
        });
        $this->sopieVideos= Cache::remember('videoSopie', now()->addMinute(15), function () {
            $data = $this->video->findAll('Sopie');  // API call
            $array = json_decode($data->getContent(), true);

            return $array['data']; // Store as collection

        });

        /****************** View share  ************************************/
        view()->share([

            /*'banner'=>$this->pub728,
            'skypper'=>$this->pub300,*/
            'debat'=> $this->debat,
            'droit'=> $this->droit,
            'event'=> $this->evt,
            'camer'=> $this->camer,
            'sopie'=> $this->sopie,
            'pub'=> $this->publicite,
            'archives'=> $this->archives,
        ]);

    }
    public function index(Request $request){
        //dd($request);
        $perPage = 10;
        $currentPage = (int) $request->get('page', 1);

        $articles = Cache::remember('articles_json', now()->addHours(12), function () {
            $data = $this->api->getArticles();   // API call
            $array = json_decode($data->getContent(), true);
            return $array['data']; // Store as collection
        });
        $firstTenArticles=array_slice($articles,0,$perPage);
        $ldJson=$this->collectionPageSchema->articles($firstTenArticles);
        $currentItems = array_slice($articles, ($currentPage - 1) * $perPage, $perPage);
        /*$currentItems = $articles
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();*/

        $paginated = new LengthAwarePaginator(
            $currentItems,
            count($articles),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $cacheKey="cache_amp_index_".$currentPage;
        //Cache::forget($cacheKey);
        //dd($ldJson);
        return Cache::remember($cacheKey, now()->addHours(12), function ()
            use ($paginated,$ldJson) {
            return view('index-amp', [
                'articles' => $paginated,
                'listItemArticles'=>$ldJson,
            ])->render();
        });
    }
    public function index1($rubrique,$sousrubrique,$slug){
        //dd($slug);
        $data=$this->api->getArticleBySlug($slug);
        $array = json_decode($data->getContent(), true);
        $article = collect($array['data']);
        $jld = $this->collectionPageSchema->newsArticle($array['data']);
        $dataSamerubrique=$this->api->getSameRubrique($article['fksousrubrique']);
        $arraySamerubrique =json_decode($dataSamerubrique->getContent(), true);
        $samerubrique= collect($arraySamerubrique['data']);
        return view('index1-amp', [
            'article' =>  $article,
            'samerubriques' =>  $samerubrique,
            'ldjson'=>$jld,

        ]);
    }
    public function index2(Request $request){
        //$isVideo=true;
        $perPage = 10;
        $currentPage = (int) $request->get('page', 1);

        $rubriques=Config::get('rubriques.map') ;
        //dd($rubriques["map"]);
        if($request->rubrique!='video'){
            $isVideo=false;
            $fksousrubrique=$rubriques[$request->sousrubrique];
            $fkrubrique=$rubriques[$request->rubrique];
            $data=$this->api->getRubriqueArticles($fksousrubrique,$fkrubrique);
            $array = json_decode($data->getContent(), true);
            $articles = collect($array['data']);
        }
        $firstTenArticles=array_slice($array['data'],0,$perPage);
        $ldJson=$this->collectionPageSchema->articles($firstTenArticles);
        $currentItems = $articles
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $paginated = new LengthAwarePaginator(
            $currentItems,
            $articles->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('index2-amp', [
            'articles' => $paginated,
            'listItemArticles'=>$ldJson,
        ])->render();
    }
    public function video(Request $request){

        $perPage=10;
        $currentPage = (int) $request->get('page', 1);
        $data=$this->video->findAll($request->sousrubrique);
        $array = json_decode($data->getContent(), true);
        $videos=$array["data"];
        $firstTenVideos=array_slice($videos,0,$perPage);
        $ldJson=$this->collectionPageSchema->videos($firstTenVideos);
        $currentItems = collect($videos)
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $paginated = new LengthAwarePaginator(
            $currentItems,
            count($videos),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('video-amp', [
            'videos' => $paginated,
            'listItemArticles'=>$ldJson,

        ])->render();
    }
}
