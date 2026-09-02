<?php

use Livewire\Component;
use App\Helpers\Helper;

new class extends Component
{
    //
    /*public $fkpays;
    public $fksousrubrique;*/
    public $plusluParAuteur=[];
    public $firstArticle;
    public $flag;
    public $sousrubrique;
    public $mostReaded=[];
    protected $auteur;

    public function mount(){

        /*$rawPlusLuParPays=$articleService->getMostReadRubriqueByCountry($this->fksousrubrique,$this->fkpays);
        $collection = collect($rawPlusLuParPays);
        $this->plusluParPays = $collection->values()->toArray();*/
        $this->firstArticle=$this->mostReaded[0];
        $this->auteur=$this->firstArticle["auteur"];
        if($this->firstArticle["fkpays"]==='F' || $this->firstArticle["fkpays"]==='ZZ')
        {
            $this->flag= "https://flagcdn.com/16x12/un.webp";
        }
        else{

            $this->flag="https://flagcdn.com/16x12/".strtolower($this->firstArticle["fkpays"]).".webp" ;
        }


        $this->sousrubrique=$this->firstArticle['sousrubrique']['sousrubrique'];
    }
};
?>

<div class="bg-white dark:bg-dark-surface p-6 rounded-xl border border-gray-100 dark:border-dark-border shadow-sm">
    <h3 class="text-lg font-bold font-heading mb-4 border-l-4 border-highlight-500 pl-3">
        <span class="inline-flex items-center gap-1 bg-brand-500 text-white text-[10px] font-bold uppercase px-2.5 py-1 rounded shadow">

            🔥Les + lus de {{$this->auteur}}
        </span>
    </h3>
    <div class="space-y-4">
        @foreach($this->mostReaded as $article)
            @php
                $hit=Helper::views_format($article['hit'],false);
                $sousrubrique=$article['sousrubrique']['sousrubrique'];
                $rubrique=$article['rubrique']['rubrique'];
                $slug=$article["slug"];
                $url=Helper::makeUrl(
                   $rubrique,
                   $sousrubrique,
                   $slug
                );

            @endphp
            <a href="/{{$url}}" class="flex items-start space-x-3 group border-b border-gray-100 dark:border-gray-800 pb-3">
                <span class="text-2xl font-black text-gray-300 dark:text-gray-600 group-hover:text-brand-500 transition">{{$loop->iteration}}</span>
                <div>
                    <h4 class="text-xs font-bold leading-snug text-gray-800 dark:text-gray-200 group-hover:text-brand-500 transition line-clamp-2">
                        {{$article['titre']}}
                    </h4>
                    <span class="text-[10px] text-gray-400 mt-1 block">{{$hit["short"]}} vues</span>
                </div>
            </a>
        @endforeach

    </div>
</div>
