<?php


use App\Http\Controllers\RssController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::prefix('rss')->group(function () {
    Route::get('/', [RssController::class, 'feed'])->name('rss.main');
    Route::get('politique', [RssController::class, 'politique'])->name('rss.politique');
    Route::get('societe', [RssController::class, 'societe'])->name('rss.societe');
    Route::get('economie', [RssController::class, 'societe'])->name('rss.economie');
    Route::get('diaspora', [RssController::class, 'diaspora'])->name('rss.diaspora');

});
