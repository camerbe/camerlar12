<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\ArticleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap ;
use Spatie\Sitemap\Tags\Url;

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
    public function index(){

        $articles= $this->articleService->getNewsForRss();

        $sitemap = Sitemap::create();

        foreach ($articles as $article) {
            $media=$article->getFirstMedia('article');
            if($media){
                $image=Helper::extractImgSrc($article->image);
                $image=Helper::parseImageUrl($image);
            }

            $sitemap->add(
                Url::create("/{$article->slug}")
                    ->setLastModificationDate(Carbon::parse($article->dateparution))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8)
                    ->addImage($image)
            );

        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
        //return $sitemap->toResponse($request);
    }
}
