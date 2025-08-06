<?php

namespace App\Providers;

use App\IRepository\IEvenementRepository;
use App\IRepository\IPubRepository;
use App\IRepository\IPubType;

use App\IRepository\IRepository;
use App\IRepository\IVideoRepository;
use App\Repositories\EvenementRepository;
use App\Repositories\PubDimensionRepository;
use App\Repositories\PubRepository;
use App\Repositories\PubTypeRepository;
use App\Repositories\VideoRepository;
use App\Services\EvenementService;
use App\Services\PubDimensionService;
use App\Services\PubService;
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

        $this->app->when(PubService::class)
            ->needs(IPubRepository::class)
            ->give(PubRepository::class);

        $this->app->when(EvenementService::class)
            ->needs(IEvenementRepository::class)
            ->give(EvenementRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
