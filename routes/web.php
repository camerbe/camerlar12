<?php


use App\Http\Controllers\AmpController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\RssController;
use App\Http\Controllers\SitemapArticleController;
use App\Http\Controllers\SitemapController;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
Route::get('/', function (ArticleService $articleService) {
    $heroArticle = collect($articleService->getArticles())->first();
    return view('home', compact('heroArticle'));
})->name('home');
*/



    /*
    |--------------------------------------------------------------------------
    | AMP (cache agressif) — DOIT être déclaré avant les routes FRONT génériques
    |--------------------------------------------------------------------------
    */
    Route::prefix('amp')->controller(AmpController::class)->group(function () {
        Route::get('accueil', 'index')->name('amp.index')->middleware('cache.response');
        Route::get('{rubrique}/{sousrubrique}/{slug}', 'index1')->name('amp.index1')->middleware('cache.response');
        Route::get('{rubrique}/{sousrubrique}', 'index2')->name('amp.index2')->middleware('cache.response');
    });
    /*
   |--------------------------------------------------------------------------
   | FRONT
   |--------------------------------------------------------------------------
   */
    Route::prefix('/')->controller(FrontEndController::class)->group(function () {
        //Route::get('/amp/{slug}', FrontEndController::class);
        Route::get('', 'laUne')->name('home');
        Route::get('auteur/{auteur}', 'index3')->name('index3');
        Route::get('video/{video}', 'index4')->name('index4');
        Route::get('{rubrique}/{sousrubrique}', 'getArticlesByRubrique')->name('articlerubrique');
        Route::get('{rub}/{sousrub}/{slug}', 'display')->name('display');
        Route::get('qui-sommes-nous', 'index5')->name('index5');



    });



    /*
    |--------------------------------------------------------------------------
    | RSS
    |--------------------------------------------------------------------------
    */
    Route::prefix('rss')->controller(RssController::class)->group(function () {
        Route::get('', 'feed')->name('rss.main');
        Route::get('diaspora',  'diaspora')->name('rss.diaspora');
        Route::get('economie', 'societe')->name('rss.economie');
        Route::get('pointdevue', 'pointdevue')->name('rss.pointdevue');
        Route::get('politique', 'politique')->name('rss.politique');
        Route::get('societe', 'societe')->name('rss.societe');
    });

    /*
    |--------------------------------------------------------------------------
    | SITEMAP (ULTRA IMPORTANT SEO)
    |--------------------------------------------------------------------------
    */
    Route::get('/sitemapindex.xml', [SitemapController::class, 'index'])->name('sitemap.index');
    Route::get('/sitemap-article.xml', [SitemapController::class, 'article'])->name('sitemap.articles');
    Route::get('/sitemap-actualites', [SitemapController::class, 'googleNews'])->name('sitemap.actualite');
    Route::get('/sitemap-politique', [SitemapController::class, 'politique'])->name('sitemap.politique');
    Route::get('/sitemap-economie', [SitemapController::class, 'economie'])->name('sitemap.economie');
    Route::get('/sitemap-societe', [SitemapController::class, 'societe'])->name('sitemap.societe');
    Route::get('/sitemap-diaspora', [SitemapController::class, 'diaspora'])->name('sitemap.diaspora');
    Route::get('/sitemap-pointdevue', [SitemapController::class, 'pointdevue'])->name('sitemap.pointdevue');

/*
|--------------------------------------------------------------------------
| FILE MANAGER
|--------------------------------------------------------------------------
*/


/*
Route::get('/{rubrique}/{sousrubrique}/{slug}', function (
    $rubrique,
    $sousrubrique,
    $slug,
    ArticleService $articleService
) {
    $oneArticle = $articleService->getArticleBySlug($slug);

    return view('article', compact('slug','oneArticle'));
});
/*Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('home');
});*/

