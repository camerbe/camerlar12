<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\ArticleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap ;
use Spatie\Sitemap\Tags\News;
use Spatie\Sitemap\Tags\Sitemap as SitemapTag;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;


class SitemapController extends Controller
{

    protected $articleService;
    //

    /**
     * @param $articleService
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }


    public function index(){

        $sitemapIndex = SitemapIndex::create();
        $sitemapIndex->add(SitemapTag::create(route('rss.main')))
            ->add(SitemapTag::create(route('rss.politique')))
            ->add(SitemapTag::create(route('rss.societe')))
            ->add(SitemapTag::create(route('rss.diaspora')))
            ->add(SitemapTag::create(route('rss.pointdevue')))
            ->add(SitemapTag::create(route('rss.economie')));

        $sitemapIndex->add(SitemapTag::create(route('sitemap.articles')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.actualite')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.politique')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.economie')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.societe')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.diaspora')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.pointdevue')));
        return response($sitemapIndex->render(), 200, ['Content-Type' => 'application/xml']);
    }
    public function article(){
        $articles=$this->articleService->getNewsForRss();
        $articleSitemap = Sitemap::create('');
        foreach ($articles as $article){
            $media=$article->getFirstMedia('article');

            $url= new Url("/{$article->slug}");
            $url->setLastModificationDate(Carbon::parse($article->dateparution))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8);
            if($media){
                $image=Helper::extractImgSrc($article->image);
                $image=Helper::parseImageUrl($image);
                $url->addImage($image);
            }
            $articleSitemap->add($url);
        }
        return response($articleSitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
    public function googleNews(){
        $articles=$this->articleService->getNewsForRss()->take(50);
        $sitemap = Sitemap::create('');
        foreach ($articles as $article){
            $media=$article->getFirstMedia('article');
            $titre=Helper::getTitle($article->countries->pays,$article->titre,$article->countries->country);
            $url= new Url("/{$article->slug}");
            $url->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
                ->setPriority(0.9);
            $url->addNews(
                name: 'Camer.be',
                language: 'fr',
                title: $titre,
                publicationDate:Carbon::parse($article->dateparution),
                options: [
                    'keywords'=>$article->keyword,

                ],


            );
            if($media){
                $image=Helper::extractImgSrc($article->image);
                $image=Helper::parseImageUrl($image);
                $url->addImage($image,$article->titre,$article->countries->pays,$titre);
            }
            $sitemap->add($url);
        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
    public function politique(){
        $articles=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','POLITIQUE')
            ->take(20);
        $sitemap = Sitemap::create('');
        foreach ($articles as $article){
            $media=$article->getFirstMedia('article');
            $titre=Helper::getTitle($article->countries->pays,$article->titre,$article->countries->country);
            $url= new Url("/{$article->slug}");
            $url->setLastModificationDate(Carbon::parse($article->dateparution))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8);
            if($media){
                $image=Helper::extractImgSrc($article->image);
                $image=Helper::parseImageUrl($image);
                $url->addImage($image,$article->titre,$article->countries->pays,$titre);
            }
            $sitemap->add($url);
        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
    public function economie(){
        $articles=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','ECONOMIE')
            ->take(20);
        $sitemap = Sitemap::create('');
        foreach ($articles as $article){
            $media=$article->getFirstMedia('article');
            $titre=Helper::getTitle($article->countries->pays,$article->titre,$article->countries->country);
            $url= new Url("/{$article->slug}");
            $url->setLastModificationDate(Carbon::parse($article->dateparution))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8);
            if($media){
                $image=Helper::extractImgSrc($article->image);
                $image=Helper::parseImageUrl($image);
                $url->addImage($image,$article->titre,$article->countries->pays,$titre);
            }
            $sitemap->add($url);
        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
    public function societe(){
        $articles=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','SOCIETE')
            ->take(20);
        $sitemap = Sitemap::create('');
        foreach ($articles as $article){
            $media=$article->getFirstMedia('article');
            $titre=Helper::getTitle($article->countries->pays,$article->titre,$article->countries->country);
            $url= new Url("/{$article->slug}");
            $url->setLastModificationDate(Carbon::parse($article->dateparution))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8);
            if($media){
                $image=Helper::extractImgSrc($article->image);
                $image=Helper::parseImageUrl($image);
                $url->addImage($image,$article->titre,$article->countries->pays,$titre);
            }
            $sitemap->add($url);
        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
    public function diaspora(){
        $articles=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','DIASPORA')
            ->take(20);
        $sitemap = Sitemap::create('');
        foreach ($articles as $article){
            $media=$article->getFirstMedia('article');
            $titre=Helper::getTitle($article->countries->pays,$article->titre,$article->countries->country);
            $url= new Url("/{$article->slug}");
            $url->setLastModificationDate(Carbon::parse($article->dateparution))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8);
            if($media){
                $image=Helper::extractImgSrc($article->image);
                $image=Helper::parseImageUrl($image);
                $url->addImage($image,$article->titre,$article->countries->pays,$titre);
            }
            $sitemap->add($url);
        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
    public function pointdevue(){
        $articles=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','POINT DE VUE')
            ->take(20);
        $sitemap = Sitemap::create('');
        foreach ($articles as $article){
            $media=$article->getFirstMedia('article');
            $titre=Helper::getTitle($article->countries->pays,$article->titre,$article->countries->country);
            $url= new Url("/{$article->slug}");
            $url->setLastModificationDate(Carbon::parse($article->dateparution))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8);
            if($media){
                $image=Helper::extractImgSrc($article->image);
                $image=Helper::parseImageUrl($image);
                $url->addImage($image,$article->titre,$article->countries->pays,$titre);
            }
            $sitemap->add($url);
        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }

}
