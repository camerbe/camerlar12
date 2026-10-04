<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\SousrubriqueResource;
use App\IRepository\IArticleRepository;
use App\Models\Article;
use App\Models\Evenement;
use App\Models\Pays;
use App\Models\Sousrubrique;
use Carbon\Carbon;
use Html2Text\Html2Text;
use http\Exception\InvalidArgumentException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


//use Intervention\Image\Laravel\Facades\Image;


class ArticleRepository extends Repository implements IArticleRepository
{
    private $perPage=10;
    /**
     * @param $modelf
     */

    private const TAG = 'articles';
    private const VERSION_KEY = 'art:version';
    private ?string $versionCache = null;
    private const RELATIONS = ['countries', 'rubrique', 'sousrubrique'];
    public function __construct(Article $article)
    {
        parent::__construct($article);
    }
    private function remember(string $key, $ttl, \Closure $callback)
    {
        return Cache::remember("art:v{$this->version()}:{$key}", $ttl, $callback);
    }

    private function flushCache(): void
    {
        Cache::forever(self::VERSION_KEY, now()->getTimestampMs());
        $this->versionCache = null;
    }

    private function version(): string
    {
        return $this->versionCache ??= (string) Cache::rememberForever(
            self::VERSION_KEY,
            fn () => now()->getTimestampMs()
        );
    }

    private function toArray($data): array
    {
        return ArticleResource::collection($data)->resolve();
    }

    private function base()
    {
        return Article::with(self::RELATIONS);
    }
    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input)
    {
        $bled=Pays::find($input['fkpays']);
        $html=new Html2Text($input['info']);
        //$image=Helper::extractImgSrc($input['image']);
        $input['chapeau']=Str::of($html->getText())->limit(160);
        $input['imageheight']=Helper::extractHeight($input['image']);
        $input['imagewidth']=Helper::extractWidth($input['image']);

        $input['keyword']= $input['keyword'].','.$input['hashtags'];
        $input['dateparution']=Carbon::parse($input['dateparution'])->format('Y-m-d H:i:s');
        $input['dateref']=$input['dateparution'];

        $input['slug']=Str::slug(Helper::getTitle($bled->pays,$input['titre'],$bled->country),'-') ;
        $input['auteur']=Str::title($input['auteur']);
        $input['source']=Str::title($input['source']);
        $input['titre']=Helper::guillemets($input['titre']);

        /*$cache="Article-By-User-".$input['fkuser'];
        Cache::forget($cache);
        return parent::create($input);*/

        $article = parent::create($input);
        $this->flushCache();
        return $article;
    }

    /**
     * @param $id
     * @return mixed
     */
    function delete($id)
    {
        $result= parent::delete($id);
        $this->flushCache();
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    function findById($id)
    {
        return new ArticleResource(parent::findById($id));

    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    function update(array $input, $id)
    {
        $current=$this->findById($id);
        $bled=Pays::find($input['fkpays']);


        $input['keyword']=$input['keyword'] .','. $input['hashtags'];
        //dd($input['keyword']);
        /*$input['keyword']=isset($input['hashtags']) ? $input['keyword'].','.$input['hashtags']
            : $current->keyword;*/
        if(isset($input['dateparution'])){
            $input['dateparution']=Carbon::parse($input['dateparution'])->format('Y-m-d H:i:s');
            $input['dateref']=$input['dateparution'];
        }

        $input['auteur']=isset($input['auteur']) ? Str::title($input['auteur']):$current->auteur;
        $input['source']=isset($input['source']) ? Str::title($input['source']):$current->auteur;
        if(isset($input['info'])){
            $html=new Html2Text($input['info']);
            $input['chapeau']=Str::of($html->getText())->limit(160);
        }
        if(isset($input['image'])){
            $input['imageheight']=Helper::extractHeight($input['image']);
            $input['imagewidth']=Helper::extractWidth($input['image']);
        }
        if(isset($input['titre']) || isset($input['fkpays'])){
            $input['slug']=Str::slug(Helper::getTitle($bled->pays,$input['titre'],$bled->country),'-') ;
        }
        $input['titre']=Helper::guillemets($input['titre']);
        /*$cache="Article-By-User-".$current->fkuser;
        Cache::forget($cache);
        return parent::update($input, $id);*/

        $result = parent::update($input, $id);
        $this->flushCache();
        return $result;
    }

    /**
     * @return mixed
     */
    function index()
    {
       return $this->remember('index', now()->addMinutes(20), fn () =>
        $this->toArray(
            Article::Published()->with(self::RELATIONS)
                ->where('dateparution','>=',now())
                ->orderByDesc('dateparution')->limit(100)->get()
            )
        );
    }

    /**
     * @param $user
     * @return mixed
     */
    function getArticleByUser($user,$perPage=10)
    {

        $page = request()->integer('page', 1);

        return $this->remember("user_{$user}_p{$page}_{$perPage}", now()->addMinutes(10), function () use ($user, $perPage) {
            $paginator = $this->base()
                ->where('fkuser', $user)
                ->orderByDesc('dateparution')
                ->paginate($perPage);

            return [
                'data' => $this->toArray($paginator->getCollection()),
                'meta' => [
                    'total'        => $paginator->total(),
                    'per_page'     => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                ],
            ];
        });


    }


    /**
     * @return mixed
     */
    function getScheduledArticle()
    {
        $articles= Article::with(['countries','rubrique','sousrubrique'])
            ->where('dateparution','>', now())
            ->orderByDesc('dateparution')
            ->get();
        return ArticleResource::collection($articles);

    }

    /**
     * @param $search
     * @return mixed
     */
    function search($search):LengthAwarePaginator
    {
        $ids = Article::query()
            ->Search($search)
            ->where('dateparution', '<=', now())
            ->orderByDesc('dateparution')
            ->limit(100)
            ->pluck('idarticle');

        return Article::whereIn('idarticle', $ids)
            ->with(['countries', 'rubrique', 'sousrubrique'])
            ->orderByDesc('dateparution')
            ->paginate(10)
            ->withQueryString();

        //return $articles ? ArticleResource::collection($articles) : null;
        //return $articles ;
    }

    /**
     * @param $cmr
     * @return mixed
     */
    function getArticles()
    {
        //Cache::forget("art:v{$this->version()}:article-flash");
        return Cache::flexible(
            "art:v{$this->version()}:article-flash",
            [300,600],   // frais 5 min, servi périmé jusqu'à 10 min
            fn () => $this->toArray(
                $this->base()
                    ->where('dateparution','<=',now())
                    ->latest('dateparution')
                    ->limit(100)
                    ->get()
            )
        );

    }

    /**
     * @param $slug
     * @return mixed
     */
    function getArticleBySlug($slug)
    {
        $article = $this->remember('slug_' . md5($slug), now()->addMinutes(10), function () use ($slug) {
            // 'none' = marqueur "introuvable" pour mettre le 404 en cache aussi
            return $this->base()->where('slug', $slug)->first() ?? 'none';
        });
        if ($article === 'none') {
            throw (new \Illuminate\Database\Eloquent\ModelNotFoundException)->setModel(Article::class);
        }

        $this->trackHit($article->getKey());

        return $article;
    }

    /**
     * @param string $period
     * @return mixed
     */
    function getTopNews(string $period)
    {
        $date = match ($period) {
            'week'  => now()->subWeek(),
            'month' => now()->subMonth(),
            'year'  => now()->subYear(),
            default => throw new \InvalidArgumentException("Invalid period: {$period}"),
        };

        return $this->remember("top_news_{$period}", now()->addHours(6), fn () =>
        $this->toArray(
            $this->base()
                ->where('dateref', '<=', $date)      // pas whereDate => index utilisable
                ->where('dateparution', '<=', now())
                ->orderByDesc('hit')->limit(5)->get()
            )
        );

    }

    /**
     * @param int $fksousrubrique
     * @return mixed
     */
    function getSameRubrique(int $fksousrubrique,int $idarticle)
    {
        return Cache::flexible(
            "art:v{$this->version()}:same_rubrique_{$fksousrubrique}",
            [1800, 21600],   // frais 30 min, servi périmé jusqu'à 6 h
            fn () => $this->toArray(
                $this->base()
                    ->where('fksousrubrique',$fksousrubrique)
                    ->where('dateparution', '<=', now())
                    ->orderByDesc('dateparution')
                    ->limit(10)
                    ->get()
            )
        );
    }

    /**
     * @param $fksousrubrique
     * @param $fkpays
     * @return mixed
     */
    function getMostReadRubriqueByCountry($fksousrubrique, $fkpays)
    {
        return Cache::flexible(
            "art:v{$this->version()}:most_read_{$fksousrubrique}_{$fkpays}",
            [1800, 21600],   // frais 30 min, servi périmé jusqu'à 6 h
            fn () => $this->toArray(
                $this->base()
                    ->where('fksousrubrique', $fksousrubrique)
                    ->where('fkpays', $fkpays)
                    ->where('dateparution', '<=', now())
                    ->orderByDesc('hit')
                    ->orderByDesc('dateparution')   // départage les égalités
                    ->limit(5)
                    ->get()
            )
        );

    }

    /**
     * @return mixed
     */
    function getMostReaded()
    {
       return Cache::flexible(
            "art:v{$this->version()}:most_readed",
            [1800, 21600],   // frais 30 min, servi périmé jusqu'à 6 h
            fn () => $this->toArray(
                $this->base()
                    ->where('dateparution', '<=', now())
                    ->orderByDesc('hit')
                    ->orderByDesc('dateparution')   // départage les égalités
                    ->limit(5)
                    ->get()
            )
        );
    }
    function getMostReadedByRubrique(int $fksousrubrique)
    {
        return Cache::flexible(
            "art:v{$this->version()}:most_readed_{$fksousrubrique}",
            [1800, 21600],   // frais 30 min, servi périmé jusqu'à 6 h
            fn () => $this->toArray(
                $this->base()
                    ->where('fksousrubrique',$fksousrubrique)
                    ->where('dateparution', '<=', now())
                    ->orderByDesc('hit')
                    ->orderByDesc('dateparution')   // départage les égalités
                    ->limit(5)
                    ->get()
            )
        );
    }

    /**
     * @param $author
     * @return mixed
     */
    function getNewsByAuthor($author)
    {
        return Cache::flexible(
            "art:v{$this->version()}:news_by_author_{$author}",
            [1800, 21600],   // frais 30 min, servi périmé jusqu'à 6 h
            fn () => $this->toArray(
                $this->base()
                    ->where('auteur',$author)
                    ->where('dateparution', '<=', now())
                    ->orderByDesc('dateparution')   // départage les égalités
                    ->limit(100)
                    ->get()
            )
        );
    }
    function getMostReadedNewsByAuthor($author)
    {
        return Cache::flexible(
            "art:v{$this->version()}:most_Readed_news_by_author_{$author}",
            [1800, 21600],   // frais 30 min, servi périmé jusqu'à 6 h
            fn () => $this->toArray(
                $this->base()
                    ->where('auteur',$author)
                    ->orderByDesc('hit')   // départage les égalités
                    ->limit(5)
                    ->get()
            )
        );
    }

    /**
     * @return mixed
     */
    function getNewsForRss()
    {

        $cacheKey = "news_for_rss";
        $articles= Cache::remember($cacheKey, now()->addDay(), function ()  {
            return  array_slice($this->index(),0,30) ;
        });

        return $articles;
    }

    /**
     * @return mixed
     */
    function allCountries()
    {
        $cacheKey = "country-cache";
        return Cache::remember($cacheKey, now()->addDay(), function ()  {
            return Pays::orderBy('pays','asc')->get();
        });
    }

    /**
     * @return mixed
     */
    function allRubrique()
    {
        $cacheKey = "sousrubrique-cache";
        return Cache::remember($cacheKey, now()->addDay(), function ()  {
            return Sousrubrique::with('rubrique')->orderBy('sousrubriques.sousrubrique','asc')
                ->join('rubriques','sousrubriques.fkrubrique','=','rubriques.idrubrique')
                ->select('*')->get();
        });
    }

    /**
     * @return mixed
     */
    function getSportArticle()
    {
        $response= Http::get(env('APP_CAMER_SPORT'));
        if($response->successful()){
            $data = $response->json();
            //dd(array_slice($data, 0, 10));
            return array_slice($data, 0, 10);
        }
        return null;
    }

    /**
     * @param $fksousrubrique
     * @param $fkrubrique
     * @return mixed
     */
    function getRubriqueArticles($fksousrubrique, $fkrubrique)
    {
        return $this->remember("rubrique_{$fkrubrique}_{$fksousrubrique}", now()->addMinutes(30), fn () =>
        $this->toArray(
            $this->base()
                ->where('fkrubrique', $fkrubrique)
                ->where('fksousrubrique', $fksousrubrique)
                ->where('dateparution', '<=', now())
                ->orderByDesc('dateparution')
                ->limit(100)->get()
        )
        );
    }

    /**
     * @param $fksousrubrique
     * @param $fkrubrique
     * @return mixed
     */
    function getOneRubriqueArticles($fksousrubrique, $fkrubrique)
    {
        $cacheKey = "cache_one_{$fksousrubrique}_{$fkrubrique}";

        $article = Cache::remember($cacheKey, now()->addHours(12), function () use ($fksousrubrique, $fkrubrique) {
            return Article::with(['countries', 'rubrique', 'sousrubrique'])
                ->where('fksousrubrique', $fksousrubrique)
                ->where('fkrubrique', $fkrubrique)
                ->where('dateparution', '<=', now())
                ->orderByDesc('dateparution')
                ->first();
        });
        return new ArticleResource($article);

    }

    public function getArticlesByCategory($fksousrubrique){
       return Cache::flexible(
            "art:v{$this->version()}:{$fksousrubrique}",
            [1800, 21600],   // frais 30 min, servi périmé jusqu'à 6 h
            fn () => $this->toArray(
                $this->base()
                    ->where('fksousrubrique',$fksousrubrique)
                    ->where('dateparution', '<=', now())
                    ->orderByDesc('dateparution')
                    ->limit(20)
                    ->get()
            )
        );
    }
    public function getCategories(){
        $cache="getCategories";
        $sousrubriques=Cache::remember($cache,now()->addDay(),function(){
            $data =Sousrubrique::CategoryRss()->orderBy('sousrubrique')->get();
            return SousrubriqueResource::collection($data)->resolve();
        });

        return $sousrubriques;

    }
    public function laUne(){
        $cache="laUne";
        //Cache::forget($cache);
        $article=Cache::remember($cache,now()->addDay(),function(){
            return Article::Published()
                ->where('dateparution', '<=', now())
                ->with(['countries', 'rubrique', 'sousrubrique'])
                ->latest('dateparution')
                ->first();
        });
        //dd(new ArticleResource($article));
        return new ArticleResource($article);

    }

    private function trackHit($getKey)
    {
        dispatch(fn () => Article::withoutEvents(
            fn () => Article::whereKey($getKey)->increment('hit')
        ))->afterResponse();
    }
    public function getFlashArticles(int $limit = 10): array
    {
        return Cache::flexible(
            "art:v{$this->version()}:article-flash",
            [300,600],   // frais 5 min, servi périmé jusqu'à 10 min
            fn () => $this->toArray(
                $this->base()
                    ->where('dateparution','<=',now())
                    ->latest('dateparution')
                    ->limit($limit)
                    ->get()
            )
        );


    }


}
