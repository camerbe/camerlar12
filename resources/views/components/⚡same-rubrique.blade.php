<?php

use Livewire\Component;
use Carbon\Carbon;
use App\Helpers\Helper;

new class extends Component
{
    public array $sameRubrique=[];
    public array $articles=[];

    public function mount(){
        $this->articles=$this->sameRubrique;

    }
};
?>
<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
@foreach($sameRubrique as $article)
    @php
        $publidhedDate=$article['dateparution'];
        $dateparutionToDisplay=Carbon::parse($publidhedDate)->translatedFormat('l d F Y H:m');
        $img=Helper::extractImgSrc($article['image']) ?? 'https://picsum.photos/1200/675?random=1';
        $titre=$article['titre'];
        $sousrubrique=$article['sousrubrique']['sousrubrique'];
        $url=Helper::makeUrl(
            $article["rubrique"]["rubrique"],
            $article["sousrubrique"]["sousrubrique"],
            $article["slug"]
        );
    @endphp
    <article class="group flex flex-col bg-white dark:bg-dark-surface rounded-xl overflow-hidden border border-gray-100 dark:border-dark-border shadow-sm hover:shadow-md transition-all">
        <a href="/{{$url}}" class="relative aspect-video overflow-hidden bg-gray-100">
            <img src="{{$img}}" width="600" height="400" alt="Chantier de modernisation d'une route au Cameroun" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            <span class="absolute top-3 left-3 bg-brand-500 text-white text-[10px] font-bold uppercase px-2.5 py-1 rounded shadow">{{$sousrubrique}}</span>
        </a>
        <div class="p-4 flex flex-col flex-1">
            <div class="text-xs text-gray-500 mb-2">{{$dateparutionToDisplay}}</div>
            <h3 class="text-sm font-bold font-heading text-gray-900 dark:text-white group-hover:text-brand-500 transition line-clamp-2 leading-snug">
                <a href="/{{$url}}">{{$titre}}</a>
            </h3>
        </div>
    </article>
@endforeach
    </div>
</div>
