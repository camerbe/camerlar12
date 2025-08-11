<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class RssController extends Controller
{
    protected $articleService;

    /**
     * @param $articleService
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }
    public function feed(){
        $items=ArticleResource::collection($this->articleService->getNewsForRss()->take(20));
        $rss = View::make('rss.feed', compact('items'));
        return response($rss, 200)->header('Content-Type', 'application/xml');
    }
    public function politique(){
        $items=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','POLITIQUE')
            ->take(20);
        $rss = View::make('rss.politique', compact('items'));
        return response($rss, 200)->header('Content-Type', 'application/xml');
    }
    public function societe(){
        $items=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','SOCIETE')
            ->take(20);
        $rss = View::make('rss.societe', compact('items'));
        return response($rss, 200)->header('Content-Type', 'application/xml');
    }
    public function economie(){
        $items=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','ECONOMIE')
            ->take(20);
        $rss = View::make('rss.societe', compact('items'));
        return response($rss, 200)->header('Content-Type', 'application/xml');
    }
    public function diaspora(){
        $items=$this->articleService->getNewsForRss()
            ->where('sousrubrique.sousrubrique','DIASPORA')
            ->take(20);
        $rss = View::make('rss.societe', compact('items'));
        return response($rss, 200)->header('Content-Type', 'application/xml');
    }

}
