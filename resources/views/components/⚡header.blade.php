<?php

use Livewire\Component;
use Carbon\Carbon;
use App\Services\ArticleService;
new class extends Component
{
    public array $trendingArticles = [];


    public function mount(ArticleService $articleService){
        $rawArticles=$articleService->getArticles();
        $collection = collect($rawArticles);
        $this->trendingArticles = $collection->slice(1, 10)->values()->toArray();

    }
};
?>
@php
    $today=Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY');
    $today=ucfirst($today);
    $duration = max(30, count($this->trendingArticles) * 8);
    $menu = [
    'accueil' => [
        'label' => 'Accueil',
        'href' => '/',
        'simple' => true,
    ],

    'actualite' => [
        'label' => 'Actualité',
        'sections' => [
            [
                'label' => 'Rubriques',
                'links' => [
                    'Diaspora' => '/camerounais-du-monde/diaspora',
                    'Économie' => '/actualites/economie',
                    'Politique' => '/actualites/politique',
                    'Religion' => '/actualites/religion',
                    'Société' => '/actualites/societe',
                    'Sport' => '/actualites/sport',
                ],
            ],
            [
                'label' => 'Divertissement',
                'links' => [
                    'Insolite' => '/actualites/insolite',
                    'Le saviez-vous' => '/fait-curieux/le-saviez-vous',
                    'People' => '/actualites/people',
                    'Sans tabou' => '/libre-parole/sans-tabou',
                ],
            ],
            [
                'label' => 'International',
                'links' => [
                    'FrançaisCamer' => '/frananglais/francaiscamer',
                    'Françafrique' => '/liens-postcoloniaux/francafrique',
                    'Géopolitique' => '/monde-pouvoir/geopolitique',
                    'Panafricanisme' => '/actualites/panafricanisme',
                ],
            ],
            [
                'label' => 'Santé',
                'links' => [
                    'Allo Docteur' => '/le-coin-sante/allo-docteur',
                    'Santé' => '/actualites/sante',
                ],
            ],
        ],
    ],

    'culture' => [
        'label' => 'Culture',
        'links' => [
            'Art' => '/culture/art',
            'Cinéma' => '/culture/cinema',
            'Livre' => '/culture/livres',
            'Musique' => '/culture/musique',
        ],
    ],

    'expression_libre' => [
        'label' => 'Libre Voix',
        'links' => [
            'Débat' => '/tribune/le-debat',
            'Droit' => '/droit/point-du-droit',
            'Point de vue' => '/analyse/point-de-vue',
        ],
    ],


];
@endphp
<div>
<header class="sticky top-0 z-50 bg-white dark:bg-dark-surface border-b border-gray-200 dark:border-dark-border shadow-sm">
    <!-- Topbar info -->
    <div class="bg-gray-900 text-white text-xs py-1.5 px-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span>📅 {{$today}}</span>
                <span class="hidden sm:inline-block text-gray-400">|</span>
                <span class="hidden sm:inline-block text-highlight-500 font-semibold">📍 Yaoundé, 28°C</span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="/video/camer" class="hover:text-highlight-500 transition">Camer TV</a>
                <span>|</span>
                <a href="/video/sopie" class="hover:text-highlight-500 transition">Sopie Prod</a>
            </div>
        </div>
    </div>

    <!-- Navigation Principale -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{route('home')}}" class="flex items-center space-x-2 group">
                    <span class="text-3xl font-extrabold font-heading tracking-tight text-brand-500 group-hover:text-brand-600 transition">
                        CAMER<span class="text-accent-500">.BE</span>
                    </span>
                <span class="hidden md:inline-block text-[10px] bg-brand-500 text-white font-bold px-1.5 py-0.5 rounded uppercase">
                        Info 24/7
                    </span>
            </a>

            <!-- Nav Links Desktop -->
            <nav class="hidden lg:flex items-center space-x-8 font-medium text-sm">

                @foreach($menu as $item)

                    {{-- Lien simple --}}
                    @if(isset($item['simple']) && $item['simple'])
                        <a href="{{ $item['href'] }}"
                           class="text-brand-500 font-bold border-b-2 border-brand-500 py-1">
                            {{ $item['label'] }}
                        </a>

                        {{-- Mega menu --}}
                    @elseif(isset($item['sections']))
                        <div class="relative group">
                            <button class="text-gray-700 dark:text-gray-200 hover:text-brand-500 transition">
                                {{ $item['label'] }}
                            </button>

                            <div class="absolute left-0 top-full mt-4 hidden group-hover:block bg-white dark:bg-gray-900 shadow-xl rounded-xl p-6 w-[700px] z-50">
                                <div class="grid grid-cols-4 gap-3">

                                    @foreach($item['sections'] as $section)
                                        <div>
                                            <h4 class="font-bold text-sm mb-2 text-brand-500">
                                                {{ $section['label'] }}
                                            </h4>

                                            <ul class="space-y-1">
                                                @foreach($section['links'] as $label => $link)
                                                    <li>
                                                        <a href="{{ $link }}"
                                                           class="text-gray-600 dark:text-gray-300 hover:text-brand-500 text-sm">
                                                            {{ $label }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- Dropdown simple --}}
                    @elseif(isset($item['links']))
                        <div class="relative group">
                            <button class="text-gray-700 dark:text-gray-200 hover:text-brand-500 transition">
                                {{ $item['label'] }}
                            </button>

                            <div class="absolute hidden group-hover:block bg-white dark:bg-gray-900 shadow-lg rounded-lg mt-3 py-2 w-56">
                                @foreach($item['links'] as $label => $link)
                                    <a href="{{ $link }}"
                                       class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                @endforeach

            </nav>
            
            <!-- Actions -->
            <div class="flex items-center space-x-4">
                <button aria-label="Rechercher" class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                <a href="#" class="hidden sm:inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-gray-900 bg-highlight-500 hover:bg-highlight-600 rounded-md shadow transition">
                    Abonnez-vous
                </a>
            </div>
        </div>
    </div>

</header>
<div class="bg-brand-500 text-white py-2 px-4 shadow-inner">
    <div class="max-w-7xl mx-auto flex items-center space-x-3 text-xs">
        <span class="font-extrabold uppercase bg-white text-brand-500 px-2 py-0.5 rounded tracking-wide shrink-0">
            FLASH INFO
        </span>
        <div class="overflow-hidden whitespace-nowrap relative w-full">
            <div class="animate-marquee font-medium" style="animation-duration: {{ $duration }}s;">
                <livewire:flash-info :articles="$trendingArticles"/>
            </div>
        </div>
    </div>
</div>
</div>
