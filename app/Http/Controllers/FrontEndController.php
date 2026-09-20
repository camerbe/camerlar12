<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Helpers\Helper;
use App\Http\Controllers\api\V1\ArticleController;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\EvenementController;
use App\Http\Controllers\api\V1\PubController;
use App\Http\Controllers\api\V1\VideoController;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Services\RubriqueRegistry;
use App\Services\SchemaOrg\CollectionPageSchema;
use Carbon\Carbon;
use Html2Text\Html2Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

#[AllowDynamicProperties] class FrontEndController extends Controller
{
    //
    protected $api;
    protected $apiAuth;
    protected $video;
    protected $pub;
    protected $event;
    private $debat;
    private $droit;
    private $pub728;
    private $pub300;
    private $archives;
    private $latestArticle;
    private $listItemArticle;
    private $collectionPageSchema;
    private $evenement;

    /**
     * @param $articleService
     */
    public function __construct(
        ArticleController $api ,
        VideoController $video,
        PubController $pub,
        AuthController $apiAuth,
        CollectionPageSchema $collectionPageSchema,
        EvenementController $event,
    )
    {
        $this->api = $api;
        $this->video = $video;
        $this->pub = $pub;
        $this->collectionPageSchema = $collectionPageSchema;
        $this->apiAuth=$apiAuth;
        $this->event=$event;

        /*-------------------- Accueil --------------------------------------------*/
        $this->latestArticle=$this->api->laUne();
        $array=json_decode($this->api->getArticles()->getContent(), true);
        $this->listItemArticle=array_slice($array["data"],0,10);

        /*-----------------------------------------------------------------*/
        $this->debat= Cache::remember('articles_debat_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(27,25);   // API call
            $array = json_decode($data->getContent(), true);
            return $array['data']; // Store as collection
        });
        $this->droit= Cache::remember('articles_droit_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(33,30);   // API call
            $array = json_decode($data->getContent(), true);
            return $array['data'];
        });
        //------------------ Video Sopie
        $data=$this->video->getOneVideo('Sopie');
        $array = json_decode($data->getContent(), true);
        $this->sopie= $array['data'];
        //------------------ Video Camer
        $data=$this->video->getOneVideo('Camer');
        $array = json_decode($data->getContent(), true);
        $this->camer = $array['data'];
        /**************** Archives *********************/
        $cacheKey=md5('archive');
        //Cache::forget( $cacheKey);
        $this->archives = Cache::remember($cacheKey, now()->addDay(1), function () {

            $data1 = $this->api->getTopNews('week');
            $arrayweek = json_decode($data1->getContent(), true);
            $arrayweek = $arrayweek["success"] ? $arrayweek["data"]:[];

            $data2 = $this->api->getTopNews('month');
            $arraymonth = json_decode($data2->getContent(), true);
            $arraymonth = $arraymonth["success"] ? $arraymonth["data"]:[];

            $data3 = $this->api->getTopNews('year');
            $arrayyear = json_decode($data3->getContent(), true);
            $arrayyear = $arrayyear["success"] ? $arrayyear["data"]:[];
            //dd($arrayyear);
            return [
                'week' => $arrayweek?? [],
                'month' => $arraymonth ?? [],
                'year' => $arrayyear ?? [],
            ] ;
        });
        /**************** pub *********************/
        $data728=$this->pub->getCachedPub('728');
        $array728 = json_decode($data728->getContent(), true);
        $this->pub728 = $array728['data'] ?? [];

        $data300=$this->pub->getCachedPub('300');
        $array300 = json_decode($data300->getContent(), true);
        $this->pub300 = $array300['data'] ?? [];
        /**************** Event *********************/
        $dataEvent=$this->event->getCachedEvenements();
        $arrayEvent= json_decode($dataEvent->getContent(), true);
        $this->evenement = $arrayEvent['data'] ?? [];

        /****************** View share  ************************************/
        view()->share([
            'archives'=>$this->archives,
            'banner'=>$this->pub728,
            'skypper'=>$this->pub300,
            'camer'=>$this->camer,
            'sopie'=>$this->sopie,
            'droit'=>$this->droit,
            'debat'=>$this->debat,
            'event'=>$this->evenement,
        ]);
    }



    public function laUne(){
        /*$data = $this->api->getArticles();   // API call
        $heroArticle=$this->latestArticle;
        $array = json_decode($heroArticle->getContent(), true);*/

        $heroArticle=$this->latestArticle();
        $this->listItemArticle=$this->collectionPageSchema->articles($this->listItemArticle);

        return view('home', [
            'listItemArticles'=>$this->listItemArticle,
            'heroArticle'=> $heroArticle,
        ]);

    }

    public function display(string $rubrique,string $sousrubrique,$slug){
        $plusLus=$sameRubrique=$oneArticle=null;
        $oneArticle =new ArticleResource($this->api->getArticleBySlug($slug));
        $array = json_decode($oneArticle->getContent(), true);
        $oneArticle =  $array['data'];


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
            /*$camer=$this->camer;
            $sopie=$this->sopie;*/

            //dd($sameRubrique);
        }
        $url=Helper::makeUrl(
            $oneArticle["rubrique"]["rubrique"],
            $oneArticle["sousrubrique"]["sousrubrique"],
            $oneArticle["slug"]
        );
        $url=config('app.url')."/".$url;
        $title=Helper::getTitle( $oneArticle["countries"]["pays"],$oneArticle["titre"],$oneArticle["countries"]["country"]);
        //dd($oneArticle["keyword"]);
        $keywords=Helper::getRealKeywords($oneArticle["keyword"]);
        //dd($keywords);
        $sousrub=Str::title($oneArticle["sousrubrique"]["sousrubrique"]);
        $description =Str::limit($oneArticle["chapeau"],155);
        $published_time=Carbon::parse($oneArticle['dateparution'])->toIso8601String();
        $modified_time=Carbon::now()->toIso8601String();
        $source=$oneArticle["source"];
        $image=$oneArticle["image_url"]??'https://picsum.photos/600/400?random=2';
        $image_height=$oneArticle["image_height"];
        $image_width=$oneArticle["image_width"];
        $html=new Html2Text($oneArticle['info']);
        $wordCount=Helper::countArticleCharacters($html->getText());
        $author=$oneArticle["auteur"];
        $authorUrl=config('app.url')."/auteur/".$author;
        $geoplacename=Str::title($oneArticle["countries"]["pays"]);
        $hit=$oneArticle["hit"];
        $jld = $this->collectionPageSchema->newsArticle($oneArticle);

        return  view('article',
            [
                'slug'=>$oneArticle["slug"],
                'oneArticle'=>$oneArticle,
                'plusLus'=>$plusLus,
                'sameRubrique'=>$sameRubrique,
                'ldjson'=>$jld
            ]);
    }

    public function getArticlesByRubrique(Request $request){



        $fksousrubrique = RubriqueRegistry::idFor($request->sousrubrique);
        $fkrubrique = RubriqueRegistry::idFor($request->rubrique);
        $cacheKey=md5($request->rubrique.$request->sousrubrique);
        $rubriqueArticles=Cache::remember($cacheKey,now()->addMinute(15),function() use($fkrubrique,$fksousrubrique){
            $data= $this->api->getRubriqueArticles($fksousrubrique,$fkrubrique);
            $array = json_decode($data->getContent(), true);
            return $array['data'];
        });

        $cache=md5($request->sousrubrique);

        $mostReaded=Cache::remember($cache,now()->addMinute(15),function() use($fksousrubrique){
            $data= $this->api->getMostReadedByRubrique($fksousrubrique);
            $array = json_decode($data->getContent(), true);
            return $array['data'];
        });
        //dd($mostReaded);
        $heroArticle=$rubriqueArticles[0]?? null;
        $this->listItemArticle=array_slice($rubriqueArticles,0,10);
        //dd($this->getListItems($this->listItemArticle));
        return view('index2',[
            'rubriqueArticles'=>$rubriqueArticles,
            'heroArticle'=>$heroArticle,
            'mostReaded'=>$mostReaded,
            'listItemArticles'=>$this->collectionPageSchema->articles($this->listItemArticle),

        ]);
    }

    public function index3(string $auteur){
        $cacheKey=md5($auteur);
        $articles=Cache::remember($cacheKey,now()->addHour(1),function() use($auteur){
            $data=$this->api->getNewsByAuthor($auteur);
            $array = json_decode($data->getContent(), true);
            return $array['data'];
        });
        $cache=md5("most_readed_".$auteur);
        $mostReaded=Cache::remember($cache,now()->addHours(1),function() use($auteur){
            $data= $this->api->getMostReadedNewsByAuthor($auteur);
            $array = json_decode($data->getContent(), true);
            return $array['data'];
        });

        $heroArticle=$articles[0]?? null;
        //$this->listItemArticle=array_slice($articles,0,10);
        /*$debat=$this->debat;
        $droit=$this->droit;
        $sopie=$this->sopie;
        $camer=$this->camer;*/

        return view('index3',[
            'articles'=>$articles,
            'heroArticle'=>$heroArticle,
            'mostReaded'=>$mostReaded,
            'listItemArticles'=>$this->collectionPageSchema->articles(array_slice($articles,0,10)),

        ]);
    }
    public function index4(Request $request){

        $video= ucfirst($request->video) ;
        //dd($video);
        //$cacheKey=$video=='Camer' ? md5("VideoCamer") :md5("VideoSopie") ;
        $videos = in_array($video, ['Camer', 'Sopie'])
            ? Cache::remember($video, now()->addDay(), function () use ($video) {
                $data = $this->video->findAll($video);
                $array = json_decode($data->getContent(), true);
                return $array['data'] ?? [];
            })
            : [];


        //dd($videos);
        //dd($cacheKey.' '. $videos);
        $firstTenVideos=array_slice($videos,0,10);

        return view('index4',[
            'videos'=>$videos,
            'listItemVideos'=>$this->collectionPageSchema->videos($firstTenVideos),

        ]);
    }
    public function index5(){
        $heroArticle=$this->latestArticle();
        return view('index5',[
            'heroArticle'=>$heroArticle,
       ]);
    }

    private  function latestArticle(){
        $article=$this->latestArticle;
        $array = json_decode($article->getContent(), true);
        return $array['data'] ?? [];
    }
    public function login(Request $request){
        $response=$this->apiAuth->login($request);
        dd($response);
    }

}
