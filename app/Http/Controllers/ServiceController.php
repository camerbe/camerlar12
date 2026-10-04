<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Services\AuthService;
use App\Services\EvenementService;
use App\Services\PubService;
use App\Services\SchemaOrg\CollectionPageSchema;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ServiceController extends Controller
{
    //

    public function __construct(
        protected ArticleService $api,
        protected VideoService $video,
        protected PubService $pub,
        protected AuthService $apiAuth,
        protected CollectionPageSchema $collectionPageSchema,
        protected EvenementService $event


    )
    {
    }
    public function getLatestArticle(){
        return $this->api->laUne();
    }
    public function getListItemArticle(): array
    {
        return $this->api->getFlashArticles(10);
    }
    public function getDebat(): array
    {
        return Cache::remember('articles_debat_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(27, 25);
            $array = json_decode($data->getContent(), true);
            return $array['data'] ?? [];
        });
    }
    public function getDroit(): array
    {
        return Cache::remember('articles_droit_json', now()->addHours(12), function () {
            $data = $this->api->getOneRubriqueArticles(33, 30);
            $array = json_decode($data->getContent(), true);
            return $array['data'] ?? [];
        });
    }
    public function getArchives(): array
    {
        $cacheKey = md5('archive');
        return Cache::remember($cacheKey, now()->addDay(1), function () {
            $data1 = $this->api->getTopNews('week');
            $arrayweek = json_decode($data1->getContent(), true);

            $data2 = $this->api->getTopNews('month');
            $arraymonth = json_decode($data2->getContent(), true);

            $data3 = $this->api->getTopNews('year');
            $arrayyear = json_decode($data3->getContent(), true);

            return [
                'week' => ($arrayweek["success"] ?? false) ? $arrayweek["data"] : [],
                'month' => ($arraymonth["success"] ?? false) ? $arraymonth["data"] : [],
                'year' => ($arrayyear["success"] ?? false) ? $arrayyear["data"] : [],
            ];
        });
    }
    public function getPub728(): array
    {
        $data = $this->pub->getCachedPub('728');
        return json_decode($data->getContent(), true)['data'] ?? [];
    }

    public function getPub300(): array
    {
        $data = $this->pub->getCachedPub('300');
        return json_decode($data->getContent(), true)['data'] ?? [];
    }

    public function getPubIframe(): array
    {
        $data = $this->pub->getIframePub();
        return json_decode($data->getContent(), true)['data'] ?? [];
    }

    public function getEvenement(): array
    {
        $data = $this->event->getCachedEvenements();
        return json_decode($data->getContent(), true)['data'] ?? [];
    }

    // 3. Gestion propre du partage de vues (à placer idéalement dans un ServiceProvider ou une méthode boot)
    public function registerViewComposers()
    {
        view()->composer('*', function (View $view) {
            static $videos = null;

            // Partage des données globales uniquement si une vue est rendue
            $view->with([
                'archives' => $this->getArchives(),
                'banner' => $this->getPub728(),
                'skypper' => $this->getPub300(),
                'droit' => $this->getDroit(),
                'debat' => $this->getDebat(),
                'event' => $this->getEvenement(),
                'iframe' => $this->getPubIframe(),
            ]);

            // Vidéos chargées une seule fois
            $videos ??= [
                'sopie' => $this->loadVideo('Sopie'),
                'camer' => $this->loadVideo('Camer'),
            ];

            $view->with($videos);
        });
    }
    private function loadVideo(string $name): array
    {
        try {
            $response = $this->video->getOneVideo($name);

            return json_decode($response->getContent(), true)['data'] ?? [];
        }
        catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

}
