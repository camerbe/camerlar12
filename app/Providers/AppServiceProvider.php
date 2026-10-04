<?php

namespace App\Providers;

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(ServiceController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        //
        if (app()->environment('local')) {
            DB::listen(function ($query) {
                Log::info(sprintf(
                    '[SQL] %s | %sms | bindings: %s',
                    $query->sql,
                    $query->time,
                    json_encode($query->bindings)
                ));
            });
        }

        View::composer('*', function ($view) {
            $rssFeeds = [
                ['title' => 'Camer.be - le flux rss', 'url' => route('rss.main')],
                ['title' => 'Camer.be - People', 'url' => route('rss.people')],
                ['title' => 'Camer.be - Sport', 'url' => route('rss.sport')],
                ['title' => 'Camer.be - Politique', 'url' => route('rss.politique')],
                ['title' => 'Camer.be - Economie', 'url' => route('rss.economie')],
                ['title' => 'Camer.be - Santé', 'url' => route('rss.sante')],
                ['title' => 'Camer.be - Société', 'url' => route('rss.societe')],
                ['title' => 'Camer.be - Diaspora', 'url' => route('rss.diaspora')],
                ['title' => 'Camer.be - Point de vue', 'url' => route('rss.pointdevue')],
                ['title' => 'Camer.be - Réligion', 'url' => route('rss.religion')],
                ['title' => 'Camer.be - Géopolitique', 'url' => route('rss.geopolitique')],
                ['title' => 'Camer.be - Sérail', 'url' => route('rss.serail')],
                ['title' => 'Camer.be - Panafricanisme', 'url' => route('rss.panafricanisme')],
            ];
            $view->with('rssFeeds', $rssFeeds);
        });


    }


}
