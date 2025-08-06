<?php

use App\Http\Controllers\api\V1\EvenementController;
use App\Http\Controllers\api\V1\PubController;
use App\Http\Controllers\api\V1\PubDimensionController;
use App\Http\Controllers\api\V1\RubriqueController;
use App\Http\Controllers\api\V1\TypePubController;
use App\Http\Controllers\api\V1\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::prefix('videos')->controller(VideoController::class)->group(function () {
    Route::get('videosem', 'getVideoWeek');
    Route::get('videorandom', 'getRandomVideo');
    Route::get('videocamer', 'getCamerVideo');
    Route::get('videofind/{videofind}', 'findAll');
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

Route::apiResources([
    "events"=>EvenementController::class,
    "pubs"=>PubController::class,
    "pubdimensions"=>PubDimensionController::class,
    "rubriques"=>RubriqueController::class,
    "typepubs"=>TypePubController::class,
    "videos"=>VideoController::class,

]);

