<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\V1\ArticleController;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\EvenementController;
use App\Http\Controllers\api\V1\PubController;
use App\Http\Controllers\api\V1\VideoController;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Services\RubriqueRegistry;
use App\Services\SchemaOrg\CollectionPageSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FrontEndController extends Controller
{
    protected $api;
    protected $apiAuth;
    protected $video;
    protected $pub;
    protected $event;
    protected $collectionPageSchema;

    private ?array $layoutData = null;

    public function __construct(
        ArticleController $api,
        VideoController $video,
        PubController $pub,
        AuthController $apiAuth,
        CollectionPageSchema $collectionPageSchema,
        EvenementController $event,
    ) {
        $this->api = $api;
        $this->video = $video;
        $this->pub = $pub;
        $this->apiAuth = $apiAuth;
        $this->event = $event;
        $this->collectionPageSchema = $collectionPageSchema;

        // Toutes les données du layout ne sont chargées qu'au moment où une
        // vue est réellement rendue (une réponse sans vue — redirect, abort,
        // exception — ne paie plus rien), et chaque source est en cache.
        view()->composer('*', function ($view) {
            $view->with($this->layoutData());
        });
    }

    /* ==================================================================
     |  Données communes au layout — tout en cache, chargé 1 fois/requête
     * ================================================================== */

    private function layoutData(): array
    {
        return $this->layoutData ??= [
            'archives' => $this->archives(),
            'banner'   => $this->pub('728'),
            'skypper'  => $this->pub('300'),
            'droit'    => $this->rubriqueBlock(33, 30, 'front.droit', 12 * 60),
            'debat'    => $this->rubriqueBlock(27, 25, 'front.debat', 12 * 60),
            'event'    => $this->events(),
            'iframe'   => $this->iframePub(),
            'sopie'    => $this->video('Sopie'),
            'camer'    => $this->video('Camer'),
        ];
    }

    private function decode($response): array
    {
        return json_decode($response->getContent(), true);
    }

    private function rubriqueBlock(int $rubrique, int $limit, string $key, int $minutes): array
    {
        return Cache::remember($key, now()->addMinutes($minutes), function () use ($rubrique, $limit) {
            return $this->decode($this->api->getOneRubriqueArticles($rubrique, $limit))['data'] ?? [];
        });
    }

    private function archives(): array
    {
        return Cache::remember('front.archives', now()->addDay(), function () {
            $top = function (string $period) {
                $response = $this->decode($this->api->getTopNews($period));
                return $response['success'] ? ($response['data'] ?? []) : [];
            };

            return [
                'week'  => $top('week'),
                'month' => $top('month'),
                'year'  => $top('year'),
            ];
        });
    }

    private function pub(string $format): array
    {
        return $this->decode($this->pub->getCachedPub($format))['data'] ?? [];
    }

    private function iframePub(): array
    {
        // sans cache interne visible côté contrôleur : on le met ici
        return Cache::remember('front.pubIframe', now()->addHours(12), function () {
            return $this->decode($this->pub->getIframePub())['data'] ?? [];
        });
    }

    private function events(): array
    {
        return $this->decode($this->event->getCachedEvenements())['data'] ?? [];
    }

    private function video(string $name): array
    {

        return Cache::remember('front.video.' . $name, now()->addHours(6), function () use ($name) {
            try {
                return $this->decode($this->video->getOneVideo($name))['data'] ?? [];
            } catch (\Throwable $e) {
                report($e);
                return [];
            }
        });
    }

    private function latestArticle()
    {

        return $this->decode($this->api->laUne())['data'] ?? [];

    }

    private function flashArticles(): array
    {
        // AVANT : requête non cachée à chaque requête du site
        return Cache::remember('front.flash', now()->addMinutes(5), function () {
            return $this->decode($this->api->getFlashArticles(10))['data'] ?? [];
        });
    }

    /* ==================================================================
     |  Pages
     * ================================================================== */

    public function laUne()
    {
        return view('home', [
            'listItemArticles' => $this->collectionPageSchema->articles($this->flashArticles()),
            'heroArticle'      => $this->latestArticle(),
        ]);
    }

    public function display(string $rubrique, string $sousrubrique, $slug)
    {
        $oneArticle = $this->decode(new ArticleResource($this->api->getArticleBySlug($slug)))['data'] ?? null;

        if (! $oneArticle) {
            abort(404);
        }

        $plusLus = Cache::remember(
            'front.pluslus.' . $oneArticle['fksousrubrique'] . '.' . $oneArticle['fkpays'],
            now()->addHours(12),
            function () use ($oneArticle) {
                return $this->decode(
                    $this->api->getMostReadRubriqueByCountry($oneArticle['fksousrubrique'], $oneArticle['fkpays'])
                )['data'] ?? [];
            }
        );

        $sameRubrique = Cache::remember(
            'front.samerub.' . $oneArticle['fksousrubrique'] . '.' . $oneArticle['id'],
            now()->addMinutes(15),
            function () use ($oneArticle) {
                return $this->decode(
                    $this->api->getSameRubrique($oneArticle['fksousrubrique'], $oneArticle['id'])
                )['data'] ?? [];
            }
        );

        return view('article', [
            'slug'         => $oneArticle['slug'],
            'oneArticle'   => $oneArticle,
            'plusLus'      => $plusLus,
            'sameRubrique' => $sameRubrique,
            'ldjson'       => $this->collectionPageSchema->newsArticle($oneArticle),
        ]);
    }

    public function getArticlesByRubrique(Request $request)
    {
        $fksousrubrique = RubriqueRegistry::idFor($request->sousrubrique);
        $fkrubrique     = RubriqueRegistry::idFor($request->rubrique);

        $rubriqueArticles = Cache::remember(
            'front.rubrique.' . $request->rubrique . '.' . $request->sousrubrique,
            now()->addMinutes(15),
            function () use ($fkrubrique, $fksousrubrique) {
                return $this->decode(
                    $this->api->getRubriqueArticles($fksousrubrique, $fkrubrique)
                )['data'] ?? [];
            }
        );

        $mostReaded = Cache::remember(
            'front.mostreaded.' . $request->sousrubrique,
            now()->addMinutes(15),
            function () use ($fksousrubrique) {
                return $this->decode(
                    $this->api->getMostReadedByRubrique($fksousrubrique)
                )['data'] ?? [];
            }
        );

        return view('index2', [
            'rubriqueArticles' => $rubriqueArticles,
            'heroArticle'      => $rubriqueArticles[0] ?? null,
            'mostReaded'       => $mostReaded,
            'listItemArticles' => $this->collectionPageSchema->articles(array_slice($rubriqueArticles, 0, 10)),
        ]);
    }

    public function index3(string $auteur)
    {
        $key = Str::slug($auteur);

        $articles = Cache::remember('front.auteur.' . $key, now()->addHour(), function () use ($auteur) {
            return $this->decode($this->api->getNewsByAuthor($auteur))['data'] ?? [];
        });

        $mostReaded = Cache::remember('front.auteur.mostreaded.' . $key, now()->addHour(), function () use ($auteur) {
            return $this->decode($this->api->getMostReadedNewsByAuthor($auteur))['data'] ?? [];
        });

        return view('index3', [
            'articles'        => $articles,
            'heroArticle'     => $articles[0] ?? null,
            'mostReaded'      => $mostReaded,
            'listItemArticles' => $this->collectionPageSchema->articles(array_slice($articles, 0, 10)),
        ]);
    }

    public function index4(Request $request)
    {
        $video = ucfirst($request->video);

        $videos = in_array($video, ['Camer', 'Sopie'])
            ? Cache::remember('front.videos.' . $video, now()->addDay(), function () use ($video) {
                return $this->decode($this->video->findAll($video))['data'] ?? [];
            })
            : [];

        return view('index4', [
            'videos'         => $videos,
            'listItemVideos' => $this->collectionPageSchema->videos(array_slice($videos, 0, 10)),
        ]);
    }

    public function index5()
    {
        return view('index5', ['heroArticle' => $this->latestArticle()]);
    }

    public function contact()
    {
        return view('mail.form-contact', ['heroArticle' => $this->latestArticle()]);
    }

    public function login(Request $request)
    {
        return $this->apiAuth->login($request);
    }
}
