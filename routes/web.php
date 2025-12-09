<?php


use App\Http\Controllers\AmpController;
use App\Http\Controllers\RssController;
use App\Http\Controllers\SitemapArticleController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

    Route::get('amp', [AmpController::class, 'index'])->name('amp.index');
    Route::get('amp/{rubrique}/{sousrubrique}/{slug}', [AmpController::class, 'index1'])->name('amp.index1');

    Route::get('rss', [RssController::class, 'feed'])->name('rss.main');
    Route::get('politique', [RssController::class, 'politique'])->name('rss.politique');
    Route::get('societe', [RssController::class, 'societe'])->name('rss.societe');
    Route::get('economie', [RssController::class, 'societe'])->name('rss.economie');
    Route::get('diaspora', [RssController::class, 'diaspora'])->name('rss.diaspora');
    Route::get('pointdevue', [RssController::class, 'pointdevue'])->name('rss.pointdevue');
    Route::get('/sitemapindex.xml', [SitemapController::class, 'index'])->name('sitemap.index');
    Route::get('/sitemap-article.xml', [SitemapController::class, 'article'])->name('sitemap.articles');
    Route::get('/sitemap-actualites', [SitemapController::class, 'googleNews'])->name('sitemap.actualite');
    Route::get('/sitemap-politique', [SitemapController::class, 'politique'])->name('sitemap.politique');
    Route::get('/sitemap-economie', [SitemapController::class, 'economie'])->name('sitemap.economie');
    Route::get('/sitemap-societe', [SitemapController::class, 'societe'])->name('sitemap.societe');
    Route::get('/sitemap-diaspora', [SitemapController::class, 'diaspora'])->name('sitemap.diaspora');
    Route::get('/sitemap-pointdevue', [SitemapController::class, 'pointdevue'])->name('sitemap.pointdevue');




