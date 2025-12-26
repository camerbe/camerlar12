<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\V1\EvenementController;
use App\Http\Controllers\api\V1\PubController;
use App\Http\Controllers\api\V1\VideoController;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\api\V1\ArticleController;
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

    public function __construct(
        ArticleController $api,
        EvenementController $event,
        VideoController $video,
        PubController $pub,

    ){
        $this->api=$api;
        $this->event=$event;
        $this->video=$video;
        $this->pub=$pub;


        //------------------ event
        $data=$this->event->getCachedEvenements();
        $array = json_decode($data->getContent(), true);
        $this->evt = collect($array['data']);
        //------------------ Video Camer
        $data=$this->video->getOneVideo('Camer');
        $array = json_decode($data->getContent(), true);
        $this->camer = collect($array['data']);
        //------------------ Video Camer
        $data=$this->video->getOneVideo('Sopie');
        $array = json_decode($data->getContent(), true);
        $this->sopie = collect($array['data']);
        //------------------ Pub
        $data=$this->pub->getCachedPub(300);
        $array = json_decode($data->getContent(), true);
        $this->pubblicite = collect($array['data'] ?? []);
        //dd($pub);
        $this->debat= Cache::remember('articles_debat_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(27,25);   // API call
            $array = json_decode($data->getContent(), true);
            return collect($array['data']); // Store as collection
        });
        $this->droit= Cache::remember('articles_droit_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(33,30);   // API call
            $array = json_decode($data->getContent(), true);
            return collect($array['data']); // Store as collection
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
            return collect([
                'week' => $arrayweek?? [],
                'month' => $arraymonth ?? [],
                'year' => $arrayyear ?? [],
            ]) ;
        });


        $this->camerVideos= Cache::remember('videoCamer', now()->addMinute(15), function () {
            $data = $this->video->findAll();  // API call
            $array = json_decode($data->getContent(), true);
            return collect($array['data']); // Store as collection
        });
        $this->sopieVideos= Cache::remember('videoSopie', now()->addMinute(15), function () {
            $data = $this->video->findAll('Sopie');  // API call
            $array = json_decode($data->getContent(), true);
            return collect($array['data']); // Store as collection
        });



    }
    public function index(Request $request){

        $perPage = 10;
        $currentPage = (int) $request->get('page', 1);

        $articles = Cache::remember('articles_json', now()->addHours(12), function () {
            $data = $this->api->getArticles();   // API call
            $array = json_decode($data->getContent(), true);
            return collect($array['data']); // Store as collection
        });

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

        $cacheKey="cache_amp_index_".$currentPage;
        //Cache::forget($cacheKey);
        //dd($archives);
        return Cache::remember($cacheKey, now()->addHours(12), function ()
            use ($paginated) {
            return view('index', [
                'articles' => $paginated,
                'debat'=> $this->debat,
                'droit'=> $this->droit,
                'event'=> $this->evt,
                'camer'=> $this->camer,
                'sopie'=> $this->sopie,
                'pub'=> $this->pubblicite,
                'archives'=> $this->archives,
            ])->render();
        });
    }
    public function index1($rubrique,$sousrubrique,$slug){
        //dd($slug);
        $data=$this->api->getArticleBySlug($slug);
        $array = json_decode($data->getContent(), true);
        $article = collect($array['data']);

        $dataSamerubrique=$this->api->getSameRubrique($article['fksousrubrique']);
        $arraySamerubrique =json_decode($dataSamerubrique->getContent(), true);
        $samerubrique= collect($arraySamerubrique['data']);
        return view('index1', [
            'article' =>  $article,
            'samerubriques' =>  $samerubrique,
            'debat'=> $this->debat,
            'droit'=> $this->droit,
            'event'=> $this->evt,
            'camer'=> $this->camer,
            'sopie'=> $this->sopie,
            'pub'=> $this->pubblicite,
            'archives'=> $this->archives,
        ]);
    }
    public function index2(Request $request){
        $isVideo=true;
        $perPage = 10;
        $currentPage = (int) $request->get('page', 1);

        $rubriques=[
            "camerounais-du-monde"=>31,
            "droit"=>30,
            "point-de-vue"=>30,
            "diaspora"=>34,
            "le-saviez-vous"=>35,
            "le-coin-sante"=>35,
            "francafrique"=>26,
            "liens-postcoloniaux"=>23,
            "francaiscamer"=>32,
            "fait-curieux"=>32,
            "libre-parole"=>33,
            "point-du-droit"=>33,
            "frananglais"=>29,
            "le-debat"=>27,
            "analyse"=>27,
            "tribune"=>25,
            "actualites"=>1,
            "musique"=>1,
            "livres"=>2,
            "people"=>5,
            "culture"=>6,
            "societe"=>11,
            "sante"=>13,
            "cinema"=>14,
            "insolite"=>15,
            "art"=>16,
            "economie"=>12,
            "religion"=>10,
            "people"=>5,
            "politique"=>6,
            "allo-docteur"=>37,
            "geopolitique"=>38,
            "monde-pouvoir"=>36,
            "sans-tabou"=>36,
        ];
        //dd($request->rubrique);
        if($request->rubrique!='video'){
            $isVideo=false;
            $fksousrubrique=$rubriques[$request->sousrubrique];
            $fkrubrique=$rubriques[$request->rubrique];
            $data=$this->api->getRubriqueArticles($fksousrubrique,$fkrubrique);
            $array = json_decode($data->getContent(), true);
            $articles = collect($array['data']);
        }
        elseif ($request->sousrubrique!='Camer'){
            $articles=$this->sopieVideos;

        }
        else{
            $articles=$this->camerVideos;

        }



        //dd($articles);
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
        return view('index2', [
            'articles' => $paginated,
            'debat'=> $this->debat,
            'droit'=> $this->droit,
            'event'=> $this->evt,
            'camer'=> $this->camer,
            'sopie'=> $this->sopie,
            'pub'=> $this->pubblicite,
            'archives'=> $this->archives,
            'isVideo'=> $isVideo,
        ])->render();
    }
}
