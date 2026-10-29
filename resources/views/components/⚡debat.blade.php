<?php

use Livewire\Component;
use App\Helpers\Helper;
new class extends Component
{
    public $debat;
    public $url;

    public function mount($debat){
        $this->debat=$debat;
        $this->url=Helper::makeUrl(
            $this->debat["rubrique"]["rubrique"],
            $this->debat["sousrubrique"]["sousrubrique"],
            $this->debat["slug"]
        );
    }
    //
};
?>

<div>
    <div class="bg-gray-900 text-white p-6 rounded-xl">
        <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2 mb-3">
            <h2 class="text-xl font-extrabold font-heading uppercase tracking-wide">
                le débat
            </h2>
            <a href="/tribune/le-debat">
                <span class="text-xs font-semibold text-brand-500">Tous les débats</span>
            </a>

        </div>
        <a href="/{{$this->url}}" class="relative aspect-video overflow-hidden bg-gray-100">
            <img
                src="{{$debat['image_url'] ??'https://picsum.photos/600/400?random=4' }}"
                alt="{{$debat['titre']}}"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
            />
            <p class="text-xs text-gray-300 mt-4 md:text-md">
                {{$debat['titre']}}
            </p>
        </a>
    </div>
</div>
