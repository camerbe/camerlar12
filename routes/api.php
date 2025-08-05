<?php

use App\Http\Controllers\api\V1\PubDimensionController;
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


Route::apiResources([
    "videos"=>VideoController::class,
    "typepubs"=>TypePubController::class,
    "pubdimensions"=>PubDimensionController::class,

]);

