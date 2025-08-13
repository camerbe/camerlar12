<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;
use Spatie\Sitemap\Tags\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;


class SitemapController extends Controller
{


    //



    public function index(){
        $sitemapIndex = SitemapIndex::create();
        $sitemapIndex->add(Sitemap::create(route('rss.main')))
            ->add(Sitemap::create(route('rss.politique')))
            ->add(Sitemap::create(route('rss.societe')))
            ->add(Sitemap::create(route('rss.diaspora')))
            ->add(Sitemap::create(route('rss.pointdevue')))
            ->add(Sitemap::create(route('rss.economie')));

        return response($sitemapIndex->render(), 200, ['Content-Type' => 'application/xml']);
    }

}
