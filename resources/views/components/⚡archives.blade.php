<?php

use Livewire\Component;
use App\Helpers\Helper;
use Carbon\Carbon;

new class extends Component
{
    public array $week = [];
    public array $month = [];
    public array $year = [];

    public function mount($archives){
        $this->week  = $archives['week'] ?? [];
        $this->month = $archives['month'] ?? [];
        $this->year  = $archives['year'] ?? [];


    }
};
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        @if(count($this->week)>0)
        <div class="space-y-4">
            <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-brand-500 pl-2">il y a une semaine</h4>
            <div class="flex flex-col gap-4">
            @foreach($this->week as $article)
                @php

                    $titre=$article['titre'];
                    $img=$article['image_url']?? 'https://picsum.photos/60/60?random=21';
                    $url=Helper::makeUrl(
                                $article["rubrique"]["rubrique"],
                                $article["sousrubrique"]["sousrubrique"],
                                $article["slug"]
                    );
                    $dateparution=Carbon::parse($article["dateparution"])->locale('fr');
                    $dateparution=ucfirst(
                                $dateparution->isoFormat('dddd D MMMM YYYY')
                    );
                    $hits=$article["hit"];
                @endphp
            <div class="flex items-start gap-3">
                <img src="{{$img}}" alt="{{$titre}}" class="w-9 h-9 rounded-full object-cover shrink-0">
                <div class="flex-1 bg-gray-50 dark:bg-gray-800/60 rounded-lg px-4 py-3">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{$dateparution}}</span>
                        <span class="text-[11px] text-gray-400"><flux:icon.bolt /> {{$hits}}</span>
                    </div>
                    <a href="/{{$url}}" class="block w-full group">
                        <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:underline leading-snug">{{$titre}}</p>
                    </a>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @endif
        @if(count($this->month)>0)
        <div class=" bg-gray-700  rounded-xl p-3">
            <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-accent-500 pl-2">il y a un mois</h4>
            <div class="flex flex-col gap-4">
            @foreach($month as $article)
               @php
                  $titre=$article['titre'];
                  $img=$article['image_url']?? 'https://picsum.photos/60/60?random=21';
                  $url=Helper::makeUrl(
                       $article["rubrique"]["rubrique"],
                       $article["sousrubrique"]["sousrubrique"],
                       $article["slug"]
                  );
                  $dateparution=Carbon::parse($article["dateparution"])->locale('fr');
                  $dateparution=ucfirst(
                                $dateparution->isoFormat('D MMMM YYYY')
                    );
                  $hits=$article["hit"];
               @endphp
            <div class="flex items-start gap-3">
                <img
                    src="{{$img}}"
                    alt="{{$titre}}"
                    class="md:w-15 md:h-15 w-9 h-9 md:w-15 md:h-15 rounded-full object-cover shrink-0">
                <div class="flex-1  rounded-lg px-4 py-3">

                    <a href="/{{$url}}"  class="block w-full group">
                       <p class=" line-clamp-2 text-sm font-medium text-gray-100 dark:text-white group-hover:underline leading-snug">{{$titre}}</p>
                    </a>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm  text-red-600 dark:text-white">📅 {{$dateparution}}</span>
                        <span class="text-[11px] text-gray-400 flex"><flux:icon.eye variant="micro" class="mr-1" />{{$hits}}</span>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @endif
        @if(count($this->year)>0)
        <div class=" bg-gray-700  rounded-xl p-3">
            <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-highlight-500 pl-2">il y a un an</h4>
            <div class="flex flex-col gap-4">
            @foreach($year as $article)
               @php
                  $titre=$article['titre'];
                  $img=$article['image_url']?? 'https://picsum.photos/60/60?random=21';
                  $url=Helper::makeUrl(
                       $article["rubrique"]["rubrique"],
                       $article["sousrubrique"]["sousrubrique"],
                       $article["slug"]
                  );
                  $dateparution=Carbon::parse($article["dateparution"])->locale('fr');
                  $dateparution=ucfirst(
                      $dateparution->isoFormat('D MMMM YYYY')
                    );
                  $hits=$article["hit"];
               @endphp
            <div class="flex items-start gap-3">
                <img src="{{$img}}" alt="{{$titre}}" class="md:w-15 md:h-15 w-9 h-9 rounded-full object-cover shrink-0">
                <div class="flex-1 rounded-lg px-4 py-3">
                    <a href="/{{$url}}"  class="block w-full group">
                       <p class="line-clamp-2 text-sm font-medium text-gray-100 dark:text-white group-hover:underline leading-snug">{{$titre}}</p>
                    </a>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm  text-red-700 dark:text-white">📅 {{$dateparution}}</span>
                        <span class="text-[11px] text-gray-400 flex"><flux:icon.eye variant="micro" class="mr-1" /> {{$hits}}</span>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

