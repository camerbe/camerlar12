<?php

namespace App\Providers;

use App\IRepository\IArticleRepository;
use App\IRepository\IEvenementRepository;
use App\IRepository\IPaysRepository;
use App\IRepository\IPubRepository;
use App\IRepository\IPubType;

use App\IRepository\IRepository;
use App\IRepository\IRubriqueRepository;
use App\IRepository\ISousrubriqueRepository;
use App\IRepository\IUserRepository;
use App\IRepository\IVideoRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\EvenementRepository;
use App\Repositories\PaysRepository;
use App\Repositories\PubDimensionRepository;
use App\Repositories\PubRepository;
use App\Repositories\PubTypeRepository;
use App\Repositories\RubriqueRepository;
use App\Repositories\SousRubriqueRepository;
use App\Repositories\UserRepository;
use App\Repositories\VideoRepository;
use App\Services\ArticleService;
use App\Services\EvenementService;
use App\Services\PaysService;
use App\Services\PubDimensionService;
use App\Services\PubService;
use App\Services\PubTypeService;
use App\Services\RubriqueService;
use App\Services\SousrubriqueService;
use App\Services\UserService;
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

        $this->app->when(RubriqueService::class)
            ->needs(IRubriqueRepository::class)
            ->give(RubriqueRepository::class);

        $this->app->when(SousRubriqueService::class)
            ->needs(ISousRubriqueRepository::class)
            ->give(SousRubriqueRepository::class);

        $this->app->when(PaysService::class)
            ->needs(IPaysRepository::class)
            ->give(PaysRepository::class);

        $this->app->when(UserService::class)
            ->needs(IUserRepository::class)
            ->give(UserRepository::class);

        $this->app->when(ArticleService::class)
            ->needs(IArticleRepository::class)
            ->give(ArticleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
