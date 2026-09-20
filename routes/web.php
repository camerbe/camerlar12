<?php


use App\Http\Controllers\AmpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RssController;
use App\Http\Controllers\SitemapArticleController;
use App\Http\Controllers\SitemapController;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
//use App\Livewire\Auth\ResetPassword;
/*
Route::get('/', function (ArticleService $articleService) {
    $heroArticle = collect($articleService->getArticles())->first();
    return view('home', compact('heroArticle'));
})->name('home');
*/


    Route::get('/login', [LoginController::class, 'show'])->name('login');
    /*
    |--------------------------------------------------------------------------
    | FILE MANAGER
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'laravel-filemanager'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    })->middleware('auth:web');
    /*
    |--------------------------------------------------------------------------
    | FORGOTPASSWORD - RESER PASSWORD
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest')->group(function () {
        Route::livewire('/forgot-password', 'pages::auth.forgot-password')->name('password.request');
        Route::livewire('/reset-password/{token}', 'pages::auth.reset-password')->name('password.reset');
    });


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------

Route::prefix('admin')->controller(DashboardController::class)->group(function () {
    Route::get('', 'index')->name('admin.index')->middleware('auth:web');


});*/
    Route::middleware(['web'])->prefix('admin')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('', 'index')->name('admin.index')->middleware('auth:web');
            /***********************/
            Route::get('pubdimension/create', 'create')->name('admin.pubdimension.create')->middleware('auth:web');
            Route::get('pubdimension', 'pubdimensionindex')->name('admin.pubdimension.index')->middleware('auth:web');
            Route::get('pubdimension/edit/{id}', 'pubdimensionedit')->name('admin.pubdimension.edit')->middleware('auth:web');
            /***********************/
            Route::get('rubrique/create', 'rubriquecreate')->name('admin.rubrique.create')->middleware('auth:web');
            Route::get('rubrique', 'rubriqueindex')->name('admin.rubrique.index')->middleware('auth:web');
            Route::get('rubrique/edit/{id}', 'rubriqueedit')->name('admin.rubrique.edit')->middleware('auth:web');
            /***********************/
            Route::get('pubtype/create', 'pubtypecreate')->name('admin.pubtype.create')->middleware('auth:web');
            Route::get('pubtype', 'pubtypeindex')->name('admin.pubtype.index')->middleware('auth:web');
            Route::get('pubtype/edit/{id}', 'pubtypeedit')->name('admin.pubtype.edit')->middleware('auth:web');
            /***********************/
            Route::get('sousrubrique/create', 'sousrubriquecreate')->name('admin.sousrubrique.create')->middleware('auth:web');
            Route::get('sousrubrique', 'sousrubriqueindex')->name('admin.sousrubrique.index')->middleware('auth:web');
            Route::get('sousrubrique/edit/{id}', 'sousrubriqueedit')->name('admin.sousrubrique.edit')->middleware('auth:web');
            /***********************/
            Route::get('video/create', 'videocreate')->name('admin.video.create')->middleware('auth:web');
            Route::get('video', 'videoindex')->name('admin.video.index')->middleware('auth:web');
            Route::get('video/edit/{id}', 'videoedit')->name('admin.video.edit')->middleware('auth:web');
            /***********************/
            Route::get('pub/create', 'pubcreate')->name('admin.pub.create')->middleware('auth:web');
            Route::get('pub', 'pubindex')->name('admin.pub.index')->middleware('auth:web');
            Route::get('pub/edit/{id}', 'pubedit')->name('admin.pub.edit')->middleware('auth:web');
            /***********************/
            Route::get('event/create', 'eventcreate')->name('admin.event.create')->middleware('auth:web');
            Route::get('event', 'eventindex')->name('admin.event.index')->middleware('auth:web');
            Route::get('event/edit/{id}', 'eventedit')->name('admin.event.edit')->middleware('auth:web');
            /***********************/
            Route::get('article/create', 'articlecreate')->name('admin.article.create')->middleware('auth:web');
            Route::get('article/{userid}', 'articleindex')->name('admin.article.index')->middleware('auth:web');
            Route::get('article/edit/{id}', 'articleedit')->name('admin.article.edit')->middleware('auth:web');
            Route::get('article/search/{search}', 'articlesearch')->name('admin.article.search')->middleware('auth:web');
        });

    });

    /*
    |--------------------------------------------------------------------------
    | AMP (cache agressif) — DOIT être déclaré avant les routes FRONT génériques
    |--------------------------------------------------------------------------
    */
    Route::prefix('amp')->controller(AmpController::class)->group(function () {
        Route::get('accueil', 'index')->name('amp.index')->middleware('cache.response');
        Route::get('{rubrique}/{sousrubrique}/{slug}', 'index1')->name('amp.index1')->middleware('cache.response');
        Route::get('video/{sousrubrique}', 'video')->name('amp.video')->middleware('cache.response');
        Route::get('{rubrique}/{sousrubrique}', 'index2')->name('amp.index2')->middleware('cache.response');

    });
    /*
   |--------------------------------------------------------------------------
   | FRONT
   |--------------------------------------------------------------------------
   */
    Route::prefix('/')->controller(FrontEndController::class)->group(function () {
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

