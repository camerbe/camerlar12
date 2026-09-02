<?php

use Livewire\Component;
use App\Helpers\Helper;

new class extends Component
{
    public $droit;
    public $url;
    public $img;
    public $titre;
    //
    public function mount($droit){
        $this->droit=$droit;
        $this->url=Helper::makeUrl(
            $this->droit["rubrique"]["rubrique"],
            $this->droit["sousrubrique"]["sousrubrique"],
            $this->droit["slug"]
        );
        $this->img=$droit['image_url'] ??'https://picsum.photos/600/400?random=4' ;
        $this->titre=$droit['titre'];
    }
};
?>

<div>
    <div class="bg-gray-900 text-white p-6 rounded-xl">
        <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2 mb-3">
            <h2 class="text-xl font-extrabold font-heading uppercase tracking-wide">
                le droit
            </h2>
            <a href="/droit/point-du-droit">
                <span class="text-xs font-semibold text-brand-500">Les points du droit</span>
            </a>

        </div>
        <a href="/{{$this->url}}" class="relative aspect-video overflow-hidden bg-gray-100">
            <img
                src="{{$this->img}}"
                alt="{{$this->titre}}"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
            />
            <p class="text-xs text-gray-300 mt-4 md:text-md">
                {{$this->titre}}
            </p>
        </a>
    </div>
</div>
