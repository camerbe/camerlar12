<?php

use Livewire\Component;
use App\Services\ArticleService;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Carbon\Carbon;

new class extends Component
{
    public ?array $heroArticle = null;
    public $debat;

    public array $trendingArticles = [];
    public array $sidebarArticles = [];
    public array $feedArticles = [];
    public $perPage=10;
    public $hasMore=true;
    public $mostReaded =[];

    public function mount(ArticleService $articleService){

        $rawArticles=$articleService->getArticles();
        $rawMostReaded=$articleService->getMostReaded();
        $collection = collect($rawArticles);
        $this->heroArticle=$collection->first();
        $this->trendingArticles = $collection->slice(1, 3)->values()->toArray();
        $this->sidebarArticles = $collection->slice(4, 5)->values()->toArray();

        // - Objet 4 : Tout le reste pour le fil d'actualités principal
        $this->feedArticles = $collection->slice(6,$this->perPage)->values()->toArray();
        $col= collect($rawMostReaded);
        $this->mostReaded=$col->values()->toArray();


    }
    public function loadMore(){

        $this->perPage += 6;
        $this->hasMore=$this->perPage<= count($this->feedArticles);
    }
    //
    /*public function render(){
        return view('livewire.news');
    }*/
};
?>


<div class="space-y-8">
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->

    @if($heroArticle)
        <livewire:featured-article :article="$heroArticle" />
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
                    @foreach($feedArticles as $item)
                        @php
                            $sousrubrique=Str::title($item["sousrubrique"]["sousrubrique"]);
                            $auteur=$item["auteur"];
                            $url=Helper::makeUrl(
                                $item["rubrique"]["rubrique"],
                                $item["sousrubrique"]["sousrubrique"],
                                $item["slug"]
                                );
                            $img=$item['image_url'] ?? 'https://picsum.photos/600/400?random=2';
                            $alt=$item["titre"];
                            $chapo=$item["chapeau"];
                            $dateparution=Carbon::parse($item["dateparution"])->locale('fr');
                            $dateparution=ucfirst(
                                $dateparution->isoFormat('dddd D MMMM YYYY HH:mm')
                            );
                            //$dateparution=Helper::formatShort($item["dateparution"]);
                            $flag="https://flagcdn.com/16x12/".strtolower($item["fkpays"]).".webp";
                            $titre=$item["titre"];
                        @endphp
                        {{-- Utilisation de composants Flux UI --}}

                        <article class="group flex flex-col bg-white dark:bg-dark-surface rounded-xl overflow-hidden border border-gray-100 dark:border-dark-border shadow-sm hover:shadow-md transition-all">
                            <a href="/{{$url}}" class="relative aspect-video overflow-hidden bg-gray-100">
                                <img src="{{$img}}" alt="{{$titre}}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1 bg-brand-500 text-white text-[10px] font-bold uppercase px-2.5 py-1 rounded shadow">
                                    <img src="{{ $flag }}" class="w-3 h-3 rounded-sm" alt="{{$titre}}" />
                                    {{ $sousrubrique }}
                                </span>
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <div class="text-xs text-gray-500 mb-2">{{$dateparution}}</div>
                                <h3 class="text-base font-bold font-heading text-gray-900 dark:text-white group-hover:text-brand-500 transition line-clamp-2 leading-snug">
                                    <a href="{{$url}}">{{$alt}}</a>
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
                <!-- Bouton Charger Plus -->
                @if($hasMore)
                    <div class="text-center mt-10">

                        <button
                            type="button"
                            wire:click.prevent="loadMore"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center px-6 py-3 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 font-bold text-xs rounded-full hover:bg-brand-500 dark:hover:bg-brand-500 dark:hover:text-white transition shadow-md">
                            <span wire:loading.remove wire:target="loadMore">Charger plus d'articles</span>
                            <span wire:loading wire:target="loadMore">Chargement...</span>
                        </button>
                    </div>
                @endif
            </section>

        </div>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4 space-y-6">
            {{-- 4. Sous-composant Livewire pour la Sidebar--}}
            <livewire:sidebar-news :articles="$mostReaded" />
            <livewire:debat :debat="$debat"/>

        </aside>
    </div>
</div>

