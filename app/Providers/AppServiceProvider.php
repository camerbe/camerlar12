<?php

namespace App\Providers;

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
    }
}
