<?php

use App\Http\Controllers\api\V1\ArticleController;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\EvenementController;
use App\Http\Controllers\api\V1\PaysController;
use App\Http\Controllers\api\V1\PubController;
use App\Http\Controllers\api\V1\PubDimensionController;
use App\Http\Controllers\api\V1\RubriqueController;
use App\Http\Controllers\api\V1\SousRubriqueController;
use App\Http\Controllers\api\V1\StatsController;
use App\Http\Controllers\api\V1\TypePubController;
use App\Http\Controllers\api\V1\UserController;
use App\Http\Controllers\api\V1\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/


Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::prefix('articles')->controller(ArticleController::class)->group(function () {
    Route::get('slug/{slug}', 'getArticleBySlug');
    Route::get('news', 'getArticles');
    Route::get('period/{period}', 'getTopNews');
    Route::get('same/{same}', 'getSameRubrique');
    Route::get('most/{rubrique}/{pays}', 'getMostReadRubriqueByCountry');
    Route::get('most/plus', 'getMostReaded');
    Route::get('auteur/{auteur}', 'getNewsByAuthor');
    Route::get('pays/countries', 'allCountries');
    Route::get('rubriques/categorie', 'allRubrique');
    Route::get('sport', 'getSportArticle');
    Route::get('{sousrubrique}/{rubrique}', 'getRubriqueArticles');
    Route::get('/one/{sousrubrique}/{rubrique}', 'getOneRubriqueArticles');

});
Route::prefix('videos')->controller(VideoController::class)->group(function () {
    Route::get('videosem', 'getVideoWeek');
    Route::get('videorandom', 'getRandomVideo');
    Route::get('videocamer', 'getCamerVideo');
    Route::get('videofind/{videofind}', 'findAll');
    Route::get('{onevideo}', 'getOneVideo');
});
Route::prefix('pubs')->controller(PubController::class)->group(function () {
   Route::get('pubcached/{pubcached}', 'getCachedPub');
});
Route::prefix('events')->controller(EvenementController::class)->group(function () {
   Route::get('list', 'getCachedEvenements');
});
Route::prefix('rubriques')->controller(RubriqueController::class)->group(function () {
   Route::get('list', 'allRubrique');
});
Route::prefix('sousrubriques')->controller(SousRubriqueController::class)->group(function () {
   Route::get('/rubrique/list', 'allRubrique');
   Route::get('list', 'allSousRubrique');
});
Route::prefix('pays')->controller(PaysController::class)->group(function () {
   Route::get('/camer/list', 'articleCameroon');
   Route::get('/other/list', 'articleNonCameroon');
   Route::get('list', 'allPays');
});
Route::prefix('users')->controller(UserController::class)->group(function () {
   Route::post('/activate/user', 'firstLogin');
   Route::get('/activating/{user}', 'getUserByEmail');
});
Route::post('auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function (){

    Route::prefix('articles')->controller(ArticleController::class)->group(function () {
        Route::get('adm/user/{adm}', 'getArticleByUser');

    });
    Route::prefix('pubs')->controller(PubController::class)->group(function () {
        Route::get('dimension/list', 'allPubDimension');
        Route::get('pubtype/list', 'allPubType');
    });


    Route::apiResources([
        "articles"=>ArticleController::class,
        "events"=>EvenementController::class,
        "pays"=>PaysController::class,
        "pubs"=>PubController::class,
        "pubdimensions"=>PubDimensionController::class,
        "rubriques"=>RubriqueController::class,
        "sousrubriques"=>SousRubriqueController::class,
        "stats"=>StatsController::class,
        "typepubs"=>TypePubController::class,
        "users"=>UserController::class,
        "videos"=>VideoController::class,
    ]);





});
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();

});


