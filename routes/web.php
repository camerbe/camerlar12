<?php


use App\Http\Controllers\RssController;
use App\Http\Controllers\SitemapArticleController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/


    Route::get('rss', [RssController::class, 'feed'])->name('rss.main');
    Route::get('politique', [RssController::class, 'politique'])->name('rss.politique');
    Route::get('societe', [RssController::class, 'societe'])->name('rss.societe');
    Route::get('economie', [RssController::class, 'societe'])->name('rss.economie');
    Route::get('diaspora', [RssController::class, 'diaspora'])->name('rss.diaspora');
    Route::get('pointdevue', [RssController::class, 'pointdevue'])->name('rss.pointdevue');
    Route::get('/sitemapindex.xml', [SitemapController::class, 'index'])->name('sitemap.index');
    Route::get('/sitemap.xml', [SitemapArticleController::class, 'article'])->name('sitemap.actualite');

