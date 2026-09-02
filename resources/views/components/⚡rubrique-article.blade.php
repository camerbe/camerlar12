<?php

use Livewire\Component;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

new class extends Component
{
    //
    public $heroArticle = null;
    //public array $rubriqueArticles=[];
    public array $feedArticles = [];
    public $perPage=10;
    public $hasMore=true;
    public $mostReaded;
    public $sopie;
    public $camer;
    public $debat;
    public $droit;
    public $skypper;
    public string $cacheKey;

    public function mount($rubriqueArticles){
        $this->cacheKey = "rubrique_articles_{$rubriqueArticles[0]['sousrubrique']['sousrubrique']}_" . md5(serialize(array_column($rubriqueArticles, 'id')));
        //$this->rubriqueArticles=$rubriqueArticles;
        Cache::put($this->cacheKey, $rubriqueArticles, now()->addMinutes(10));
        $this->updateFeed($rubriqueArticles);
        //$this->feedArticles=array_slice($this->rubriqueArticles, 1,$this->perPage);
    }
    public function loadMore(){

        $this->perPage += 6;
        $full = Cache::get($this->cacheKey, []);
        $this->updateFeed($full);
    }
    private function updateFeed($collection)
    {
        $feedSource = array_slice($collection, 6);
        $this->feedArticles = array_slice($feedSource, 0, $this->perPage);
        $this->hasMore = $this->perPage < count($feedSource);
    }
};
?>
<div class="space-y-8">
    @if($heroArticle)
        <livewire:featured-article :article="$heroArticle" />
    @endif
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <section>
                    <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2">
                        <h2 class="text-xl font-extrabold font-heading uppercase tracking-wide">
                            {{$heroArticle['sousrubrique']['sousrubrique']}} : Dernières Actualités
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
                                //$dateparution=Helper::formatShort($item["dateparution"]);
                                $flag="https://flagcdn.com/16x12/".strtolower($item["fkpays"]).".webp";
                                $titre=$item["titre"];
                                $dateparution=Carbon::parse($item["dateparution"])->locale('fr');
                                $dateparution=ucfirst(
                                    $dateparution->isoFormat('dddd D MMMM YYYY HH:mm')
                                );

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
                                        <a href="/{{$url}}">{{$alt}}</a>
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-2 line-clamp-3">
                                        {{$chapo}}
                                    </p>
                                    <div class="mt-auto pt-4 flex items-center justify-between text-xs text-gray-500">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{$auteur}}</span>
                                        <a href=/{{$url}}>
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
                                wire:click="loadMore"
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
                <livewire:most-readed-rubrique :mostReaded="$mostReaded" />
                <livewire:video :camer="null" :sopie="$sopie" />
                @include('partials.pub-aside')
                <livewire:debat :debat="$debat"/>
                <livewire:droit :droit="$droit"/>
                @include('partials.pub-aside')
                <livewire:video :camer="$camer" :sopie="null" />
                <livewire:skypper :skypper="$skypper"  />
            </aside>
        </div>
</div>

