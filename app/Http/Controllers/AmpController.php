<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\V1\EvenementController;
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
    public function __construct(
        ArticleController $api,
        EvenementController $event,
        VideoController $video,
    ){
        $this->api=$api;
        $this->event=$event;
        $this->video=$video;
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
        //------------------ event
        $data=$this->event->getCachedEvenements();
        $array = json_decode($data->getContent(), true);
        $event = collect($array['data']);
        //------------------ Video Camer
        $data=$this->video->getOneVideo('Camer');
        $array = json_decode($data->getContent(), true);
        $camer = collect($array['data']);
        //------------------ Video Camer
        $data=$this->video->getOneVideo('Sopie');
        $array = json_decode($data->getContent(), true);
        $sopie = collect($array['data']);
        //dd($event);
        $debat= Cache::remember('articles_debat_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(27,25);   // API call
            $array = json_decode($data->getContent(), true);
            return collect($array['data']); // Store as collection
        });
        $droit= Cache::remember('articles_droit_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(33,30);   // API call
            $array = json_decode($data->getContent(), true);
            return collect($array['data']); // Store as collection
        });
        //Cache::forget($cacheKey);

        return Cache::remember($cacheKey, now()->addHours(12), function ()
            use ($paginated,$debat,$droit,$event,$camer,$sopie) {
            return view('index', [
                'articles' => $paginated,
                'debat'=> $debat,
                'droit'=> $droit,
                'event'=> $event,
                'camer'=> $camer,
                'sopie'=> $sopie,
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
        ]);
    }
}
