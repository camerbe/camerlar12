<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Services\ArticleService;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap ;
use Spatie\Sitemap\Tags\News;
use Spatie\Sitemap\Tags\Sitemap as SitemapTag;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;


class SitemapController extends Controller
{

    protected $articleService;
    //
    private $categories;

    /**
     * @param $articleService
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        $this->categories=$this->articleService->getCategories();
    }


    public function index(){

        $sitemapIndex = SitemapIndex::create();
        $sitemapIndex->add(SitemapTag::create(route('rss.main')))
            ->add(SitemapTag::create(route('rss.politique')))
            ->add(SitemapTag::create(route('rss.societe')))
            ->add(SitemapTag::create(route('rss.diaspora')))
            ->add(SitemapTag::create(route('rss.pointdevue')))
            ->add(SitemapTag::create(route('rss.religion')))
            ->add(SitemapTag::create(route('rss.sante')))
            ->add(SitemapTag::create(route('rss.geopolitique')))
            ->add(SitemapTag::create(route('rss.serail')))
            ->add(SitemapTag::create(route('rss.panafricanisme')))
            ->add(SitemapTag::create(route('rss.people')))
            ->add(SitemapTag::create(route('rss.sport')))
            ->add(SitemapTag::create(route('rss.economie')));

        $sitemapIndex->add(SitemapTag::create(route('sitemap.articles')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.actualite')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.politique')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.economie')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.societe')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.diaspora')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.pointdevue')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.religion')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.sante')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.geopolitique')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.panafricanisme')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.people')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.serail')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.sport')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.musique')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.livres')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.cinema')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.art')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.media')));
        $sitemapIndex->add(SitemapTag::create(route('sitemap.successstory')));
        return response($sitemapIndex->render(), 200, ['Content-Type' => 'application/xml']);
    }
    public function article(){

        $articles=Cache::flexible('sitemap-article',[300,900],function (){
            return $this->articleService->getNewsForRss();
        });
        $articleSitemap = Sitemap::create('');
        foreach ($articles as $article){
            $rub=$article["rubrique"]["rubrique"];
            $sousrub=$article["sousrubrique"]["sousrubrique"];
            $sousrub=$article["sousrubrique"]["sousrubrique"];
            $slug=$article["slug"];
            $tmpUrl=Helper::makeUrl($rub,$sousrub,$slug);
            $image=$article["image_url"];
            $url= new Url("/{$tmpUrl}");
            $url->setLastModificationDate(Carbon::parse($article["dateparution"]))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8);
            if($image){
                $url->addImage($image);
            }
            $articleSitemap->add($url);
        }
        return response($articleSitemap->render(), 200, ['Content-Type' => 'application/xml'])
                ->header('Cache-Control', 'public, max-age=300');
    }
    public function googleNews(){
        $articles=Cache::flexible('googleNews',[300,900],function (){
            return $this->articleService->getNewsForRss();
        });


        $sitemap = Sitemap::create('');
        foreach ($articles as $article){
            $image=$article["image_url"];
            $rub=$article["rubrique"]["rubrique"];
            $sousrub=$article["sousrubrique"]["sousrubrique"];
            $sousrub=$article["sousrubrique"]["sousrubrique"];
            $slug=$article["slug"];
            $tmpUrl=Helper::makeUrl($rub,$sousrub,$slug);
            $titre=Helper::getTitle($article["countries"]["pays"],$article["titre"],$article["countries"]["country"]);
            $url= new Url("/{$tmpUrl}");
            $url->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
                ->setPriority(0.9);
            $url->addNews(
                name: 'Camer.be',
                language: 'fr',
                title: $titre,
                publicationDate:Carbon::parse($article["dateparution"]),
                options: [
                    'keywords'=>$article["keyword"],

                ],


            );
            if($image){
                $url->addImage($image,$article["titre"],$article["countries"]["pays"],$titre);
            }
            $sitemap->add($url);
        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml'])
                ->header('Cache-Control', 'public, max-age=300');
    }
    public function politique(){
        return $this->generateCategorySitemap('POLITIQUE');

    }
    public function economie(){
        return $this->generateCategorySitemap('ECONOMIE');

    }
    public function societe(){
        return $this->generateCategorySitemap('SOCIETE');

    }
    public function diaspora(){
        return $this->generateCategorySitemap('DIASPORA');

    }
    public function pointdevue(){
        return $this->generateCategorySitemap('POINT DE VUE');

    }
    public function religion(){
        return $this->generateCategorySitemap('RéLIGION');

    }
    public function sante(){
        return $this->generateCategorySitemap('SANTE');

    }
    public function geopolitique(){
        return $this->generateCategorySitemap('GéOPOLITIQUE');

    }
    public function serail(){
        return $this->generateCategorySitemap('SéRAIL');

    }
    public function panafricanisme(){
        return $this->generateCategorySitemap('PANAFRICANISME');

    }
    public function people(){
        return $this->generateCategorySitemap('PEOPLE');

    }
    public function sport(){
        return $this->generateCategorySitemap('SPORT');

    }
    /**
     * @return ResponseFactory|Response
     */
    public function musique(){
        return $this->generateCategorySitemap('MUSIQUE');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function livres(){
        return $this->generateCategorySitemap('LIVRES');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function cinema(){
        return $this->generateCategorySitemap('CINEMA');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function art(){
        return $this->generateCategorySitemap('ART');

    }

    /**
     * @return ResponseFactory|Response
     */
    public function media(){
        return $this->generateCategorySitemap('MéDIA');

    }
    public function successstory(){
        return $this->generateCategorySitemap('Success Story');

    }

    private function generateCategorySitemap(string $sousrubrique){
        $result = collect($this->categories)->first(function ($item) use($sousrubrique) {
            return $item['sousrubrique'] === $sousrubrique;
        });
        $id=$result['id'] ?? null;
        $cacheKey=$sousrubrique.md5($id);

        $articles=Cache::remember($cacheKey,now()->addMinute(10),function()use($id){
            return $this->articleService->getArticlesByCategory($id);
        });

        $articles = collect($articles)->take(20);
        $sitemap = Sitemap::create('');
        foreach ($articles as $article){
            $image=$article["image_url"];
            $titre=Helper::getTitle($article["countries"]["pays"],$article["titre"],$article["countries"]["country"]);
            $rub=$article["rubrique"]["rubrique"];
            $sousrub=$article["sousrubrique"]["sousrubrique"];
            $sousrub=$article["sousrubrique"]["sousrubrique"];
            $slug=$article["slug"];
            $tmpUrl=Helper::makeUrl($rub,$sousrub,$slug);
            $url= new Url("/{$tmpUrl}");
            $url->setLastModificationDate(Carbon::parse($article["dateparution"]))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8);
            if($image){

                $url->addImage($image,$article["titre"],$article["countries"]["pays"],$titre);
            }
            $sitemap->add($url);
        }
        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml'])
                ->header('Cache-Control', 'public, max-age=300');;
    }

}
