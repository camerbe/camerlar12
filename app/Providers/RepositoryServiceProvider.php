<?php

namespace App\Providers;

use App\IRepository\IPubType;

use App\IRepository\IRepository;
use App\IRepository\IVideoRepository;
use App\Repositories\PubDimensionRepository;
use App\Repositories\PubTypeRepository;
use App\Repositories\VideoRepository;
use App\Services\PubDimensionService;
use App\Services\PubTypeService;
use App\Services\VideoService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->when(VideoService::class)
            ->needs(IVideoRepository::class)
            ->give(VideoRepository::class);

        $this->app->when(PubTypeService::class)
            ->needs(IPubType::class)
            ->give(PubTypeRepository::class);

        $this->app->when(PubDimensionService::class)
            ->needs(IRepository::class)
            ->give(PubDimensionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
