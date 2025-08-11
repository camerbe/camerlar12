<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Http\Resources\ArticleResource;
use App\IRepository\IArticleRepository;
use App\Models\Article;
use App\Models\Evenement;
use App\Models\Pays;
use Carbon\Carbon;
use Html2Text\Html2Text;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


//use Intervention\Image\Laravel\Facades\Image;


class ArticleRepository extends Repository implements IArticleRepository
{
    /**
     * @param $model
     */
    public function __construct(Article $article)
    {
        parent::__construct($article);
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
        /*$img = Image::read($image);
        $input['imagewidth']=$img->width();
        $input['imageheight']=$img->height();*/
        $input['keyword']=Helper::getKeywords($input['keyword']);
        $input['dateparution']=Carbon::parse($input['dateparution'])->format('Y-m-d H:i:s');
        $input['dateref']=$input['dateparution'];
        [$fksousrubrique, $fkrubrique] = explode(':', $input['fkrubrique']);
        $input['fksousrubrique']=$fksousrubrique;
        $input['fkrubrique']=$fkrubrique;
        $input['slug']=Str::slug(Helper::getTitle($bled->pays,$input['titre'],$bled->country),'-') ;
        $input['auteur']=Str::title($input['auteur']);
        $input['source']=Str::title($input['source']);
        return parent::create($input);
    }

    /**
     * @param $id
     * @return mixed
     */
    function delete($id)
    {
        return parent::delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    function findById($id)
    {
        return parent::findById($id);

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

        $input['keyword']=isset($input['keyword']) ? Helper::getKeywords($input['keyword'])
            : $current->keyword;
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
            $image=Helper::extractImgSrc($input['image']);
            $img = Image::read($image);
            $input['imagewidth']=$img->width();
            $input['imageheight']=$img->height();
        }
        if(isset($input['titre']) || isset($input['fkpays'])){
            $input['slug']=Str::slug(Helper::getTitle($bled->pays,$input['titre'],$bled->country),'-') ;
        }
        return parent::update($input, $id);
    }

    /**
     * @return mixed
     */
    function index()
    {
        $articles= Article::with(['countries','rubrique','sousrubrique'])
            ->orderByDesc('dateparution')
            ->limit(100)
            ->get();
        return ArticleResource::collection($articles);
    }

    /**
     * @param $user
     * @return mixed
     */
    function getArticleByUser($user)
    {
        $cache="Article-By-User";
        $articles= Cache::remember($cache, now()->add(1,'day'), function () use ($user){
            return Article::with(['countries','rubrique','sousrubrique'])
                ->where('fkuser',$user)
                ->orderByDesc('dateparution')
                ->limit(100)
                ->get();
        });
        return ArticleResource::collection($articles);


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
     * @param $request
     * @return mixed
     */
    function search($request)
    {
        $article=(new Article)->newQuery();
        if(isset($request['datesearch']))
        {

            $article->where('articles.dateref',Carbon::parse($request['searcheddate'])->format('Y-m-d'))
                ->join('pays','pays.idpays','=','articles.fkpays')
                ->join('rubriques','rubriques.idrubrique','=','articles.fkrubrique')
                ->join('users','users.id','=','articles.fkuser')
                ->join('sousrubriques','sousrubriques.idsousrubrique','=','articles.fksousrubrique');

        }

        if(isset($request['titre']))
        {

            $article->where('articles.titre','like',"%".$request['titre']."%")
                ->join('pays','pays.idpays','=','articles.fkpays')
                ->join('rubriques','rubriques.idrubrique','=','articles.fkrubrique')
                ->join('users','users.id','=','articles.fkuser')
                ->join('sousrubriques','sousrubriques.idsousrubrique','=','articles.fksousrubrique');
        }
        if(isset($request['idarticle']))
        {

            $article->where('articles.idarticle',$request['idarticle'])
                ->join('pays','pays.idpays','=','articles.fkpays')
                ->join('rubriques','rubriques.idrubrique','=','articles.fkrubrique')
                ->join('users','users.id','=','articles.fkuser')
                ->join('sousrubriques','sousrubriques.idsousrubrique','=','articles.fksousrubrique');
        }
        $articles= $article->orderByDesc('dateparution')->select('*')->limit(50) ;
        return ArticleResource::collection($articles);
    }

    /**
     * @param $cmr
     * @return mixed
     */
    function getArticles($cmr = 'CM')
    {
        $isCmr = ($cmr==='CM');
        //Cache::forget('Article-CMR-list');
        //dd($isCmr);
        //Cache::forget('Article-Other-list');
        $cache = $isCmr ? 'Article-CMR-list' : 'Article-Other-list';
        $cacheExpiry = now()->addDay();

        $articles= Cache::remember($cache, $cacheExpiry, function () use ($isCmr) {
            return Article::with(['countries', 'rubrique', 'sousrubrique'])
                ->where('dateparution', '<=', now())
                ->when($isCmr,
                    fn($query) => $query->where('fkpays', 'CM'),
                    fn($query) => $query->where('fkpays', '<>', 'CM')
                )
                ->orderByDesc('dateparution')
                ->limit(100)
                ->get();
        });
        return ArticleResource::collection($articles);

    }

    /**
     * @param $slug
     * @return mixed
     */
    function getArticleBySlug($slug)
    {

        $article= Article::with(['countries', 'rubrique', 'sousrubrique'])
            ->where('slug', $slug)->first();
        if($article){
            Article::withoutEvents(function () use ($article) {
                $article->hit++;
                $article->save();
            });

        }
        return new ArticleResource($article);
    }

    /**
     * @param string $period
     * @return mixed
     */
    function getTopNews(string $period)
    {
        $cacheKey = "top_news_{$period}";
        $date = match ($period){
            'week'=>now()->subWeek(),
            'month'=>now()->subMonth(),
            'year'=>now()->subYear(),
            default => throw new InvalidArgumentException("Invalid period: {$period}"),
        };
        $articles= Cache::remember($cacheKey, now()->addDay(), function () use ($date) {
            return Article::with(['countries', 'rubrique', 'sousrubrique'])
                ->whereDate('dateref', $date->toDateString())
                ->orderByDesc('hit')
                ->limit(5)
                ->get();
        });
        return ArticleResource::collection($articles);
    }

    /**
     * @param int $fksousrubrique
     * @return mixed
     */
    function getSameRubrique(int $fksousrubrique)
    {
        $cacheKey = "same_rubrique_{$fksousrubrique}";
        $articles= Cache::remember($cacheKey, now()->addMinute(15), function () use ($fksousrubrique) {
            return Article::with(['countries', 'rubrique', 'sousrubrique'])
                ->where('fksousrubrique',$fksousrubrique)
                ->orderByDesc('dateparution')
                ->limit(10)
                ->get();
        });
        return ArticleResource::collection($articles);
    }

    /**
     * @param $fksousrubrique
     * @param $fkpays
     * @return mixed
     */
    function getMostReadRubriqueByCountry($fksousrubrique, $fkpays)
    {
        $cacheKey = "most_read_rubrique_country_{$fksousrubrique}{$fkpays}";
        $articles= Cache::remember($cacheKey, now()->addDay(), function () use ($fksousrubrique,$fkpays) {
            return Article::with(['countries', 'rubrique', 'sousrubrique'])
                ->where('fksousrubrique',$fksousrubrique)
                ->where('fkpays',$fkpays)
                ->orderByDesc('hit')
                ->limit(5)
                ->get();
        });
        return ArticleResource::collection($articles);

    }

    /**
     * @return mixed
     */
    function getMostReaded()
    {
        $cacheKey = "most_read";
        $articles= Cache::remember($cacheKey, now()->addDay(), function ()  {
            return Article::with(['countries', 'rubrique', 'sousrubrique'])
                ->orderByDesc('hit')
                ->limit(5)
                ->get();
        });
        return ArticleResource::collection($articles);
    }

    /**
     * @param $author
     * @return mixed
     */
    function getNewsByAuthor($author)
    {
        $cacheKey = "news_by_author_" . md5($author);
        $articles= Cache::remember($cacheKey, now()->addDay(), function () use($author) {
            return Article::with(['countries', 'rubrique', 'sousrubrique'])
                ->where('auteur', $author)
                ->where('dateparution', '<=', now())
                ->orderByDesc('dateparution')
                ->limit(100)
                ->get();
        });
        return ArticleResource::collection($articles);
    }

    /**
     * @return mixed
     */
    function getNewsForRss()
    {
        $cacheKey = "news_for_rss";
        $articles= Cache::remember($cacheKey, now()->addDay(), function ()  {
            return $this->index();
        });
        return ArticleResource::collection($articles);
    }
}
