<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
//use App\Traits\CreatesSitemap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap ;
//use Spatie\Sitemap\Tags\Url;

class SitemapArticleController extends Controller
{
    //use CreatesSitemap;
    protected $articleService;
    /**
     * @param $articleService
     */
    public function __construct(ArticleService  $articleService)
    {
        $this->articleService = $articleService;
    }
    public function article(){
        //dd(Sitemap::class);
        $articles= $this->articleService->getNewsForRss();
        $sitemap = Sitemap::create();
        
        foreach ($articles as $article) {
            //dd($sitemap);
            /*$sitemap->add(
                Url::create(route('news.show', $article))
                    ->setLastModificationDate(Carbon::parse($article->dateparution))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.9)
            );*/

        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
        //return $sitemap->toResponse($request);
    }
}
