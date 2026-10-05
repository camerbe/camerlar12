<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Services\ArticleService;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

new class extends Component
{
    // Seules données sérialisées dans le snapshot Livewire :
    // la clé du cache sidebar + le paginateur.
    public string $sidebarKey = '';
    public int $perPage = 10;

    public function mount(
        $debat = null,
        $droit = null,
        $sopie = null,
        $camer = null,
        $skypper = null,
        $event = null,
        $iframe = null,
    ) {
        // Les 7 jeux de données de la sidebar sont stockés en cache
        // (une entrée par visiteur) au lieu d'être sérialisés dans le
        // snapshot et renvoyés au serveur à chaque clic "Charger plus".
        $this->sidebarKey = 'home-sidebar-'.session()->getId();

        Cache::put($this->sidebarKey, [
            'debat'   => $debat,
            'droit'   => $droit,
            'sopie'   => $sopie,
            'camer'   => $camer,
            'skypper' => $skypper,
            'event'   => $event,
            'iframe'  => $iframe,
        ], now()->addMinutes(30));
    }

    public function loadMore(): void
    {
        $this->perPage += 6;
    }

    #[Computed]
    public function sidebar(): array
    {
        return Cache::get($this->sidebarKey) ?? [];
    }

    #[Computed]
    public function articles()
    {
        // Une seule requête (ou lecture de cache) par rendu, partagée
        // par tous les Computed ci-dessous. Le cache fixe évite de
        // refaire la requête complète à chaque clic "Charger plus".
        return Cache::remember('home.articles', now()->addMinutes(10), function () {
            return collect(app(ArticleService::class)->getArticles());
        });
    }

    #[Computed]
    public function heroArticle()
    {
        return $this->articles->first();
    }

    #[Computed]
    public function trendingArticles()
    {
        return $this->articles->slice(1, 3)->values();
    }

    #[Computed]
    public function sidebarArticles()
    {
        return $this->articles->slice(4, 5)->values();
    }

    #[Computed]
    public function feedArticles()
    {
        // 1 héros + 3 tendances + 5 sidebar = 9 articles déjà affichés
        // au-dessus : le flux démarre après, sans doublon.
        return $this->articles
            ->slice(9)
            ->take($this->perPage)
            ->values();
    }

    #[Computed]
    public function hasMore(): bool
    {
        return $this->perPage < $this->articles->slice(9)->count();
    }

    #[Computed]
    public function mostReaded()
    {
        return Cache::remember('home.most-readed', now()->addMinutes(30), function () {
            return app(ArticleService::class)->getMostReaded();
        });
    }
};
?>


<div class="space-y-8">

    @if($this->heroArticle)
        <livewire:featured-article :article="$this->heroArticle" />
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-8">
            <section>
                <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2">
                    <h2 class="text-xl font-extrabold font-heading uppercase tracking-wide">
                        Dernières Actualités
                    </h2>
                    <span class="text-xs font-semibold text-brand-500">Flux en direct</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    @foreach($this->feedArticles as $item)
                        @php
                            $sousrubrique = Str::title($item["sousrubrique"]["sousrubrique"]);
                            $auteur = $item["auteur"];
                            $url = Helper::makeUrl(
                                $item["rubrique"]["rubrique"],
                                $item["sousrubrique"]["sousrubrique"],
                                $item["slug"]
                            );
                            $img = $item['image_url'] ?? 'https://picsum.photos/600/400?random=2';
                            $titre = $item["titre"];
                            $chapo = $item["chapeau"];
                            $dateparution = Carbon::parse($item["dateparution"])->locale('fr');
                            $dateparution = ucfirst($dateparution->isoFormat('dddd D MMMM YYYY HH:mm'));
                            $flag = "https://flagcdn.com/16x12/".strtolower($item["fkpays"]).".webp";
                        @endphp

                        <article wire:key="feed-item-{{ $item['id'] ?? $loop->index }}"
                                 class="group flex flex-col bg-white dark:bg-dark-surface rounded-xl overflow-hidden border border-gray-100 dark:border-dark-border shadow-sm hover:shadow-md transition-all">
                            <a href="/{{$url}}" class="relative aspect-video overflow-hidden bg-gray-100">
                                <img src="{{$img}}" alt="{{$titre}}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1 bg-brand-500 text-white text-[10px] font-bold uppercase px-2.5 py-1 rounded shadow">
                                    <img src="{{ $flag }}" class="w-3 h-3 rounded-sm" alt="" />
                                    {{ $sousrubrique }}
                                </span>
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <div class="text-xs text-gray-500 mb-2">{{$dateparution}}</div>
                                <h3 class="text-base font-bold font-heading text-gray-900 dark:text-white group-hover:text-brand-500 transition line-clamp-2 leading-snug">
                                    <a href="/{{$url}}">{{$titre}}</a>
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-2 line-clamp-3">
                                    {{$chapo}}
                                </p>
                                <div class="mt-auto pt-4 flex items-center justify-between text-xs text-gray-500">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{$auteur}}</span>
                                    <a href="/{{$url}}">
                                        <span class="text-brand-500 font-semibold group-hover:translate-x-1 transition-transform">Lire &rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <!-- Skeleton Loader pendant le chargement Livewire -->
                    <div wire:loading.grid wire:target="loadMore" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        @for($i = 0; $i < 3; $i++)
                            <div class="bg-white dark:bg-dark-surface rounded-xl p-4 shadow-sm animate-pulse">
                                <div class="bg-gray-200 dark:bg-gray-700 h-48 rounded-lg mb-4"></div>
                                <div class="bg-gray-200 dark:bg-gray-700 h-4 w-1/3 rounded mb-2"></div>
                                <div class="bg-gray-200 dark:bg-gray-700 h-6 w-full rounded mb-2"></div>
                                <div class="bg-gray-200 dark:bg-gray-700 h-4 w-2/3 rounded"></div>
                            </div>
                        @endfor
                    </div>
                </div>

                @if($this->hasMore)
                    <div class="text-center mt-10">
                        <flux:button
                            wire:click="loadMore"
                            :loading="true"
                            variant="primary"
                            icon="plus"
                            class="inline-flex items-center px-6 py-3 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 font-bold text-xs rounded-full hover:bg-brand-500 dark:hover:bg-brand-500 dark:hover:text-white transition shadow-md"
                        >
                            Charger plus d'articles
                        </flux:button>
                    </div>
                @endif
            </section>
        </div>

        {{-- Sidebar : les données viennent du cache via $this->sidebar --}}
        <aside class="lg:col-span-4 space-y-6">
            <livewire:sidebar-news :articles="$this->mostReaded" />
            <livewire:video :camer="null" :sopie="$this->sidebar['sopie'] ?? null" />
            <livewire:debat :debat="$this->sidebar['debat'] ?? null"/>
            <livewire:pub-iframe :iframe="$this->sidebar['iframe'] ?? null"/>
            <livewire:droit :droit="$this->sidebar['droit'] ?? null"/>
            <livewire:video :camer="$this->sidebar['camer'] ?? null" :sopie="null" />
            <livewire:skypper :skypper="$this->sidebar['skypper'] ?? null" />
            <livewire:events :event="$this->sidebar['event'] ?? null" />
        </aside>
    </div>
</div>
