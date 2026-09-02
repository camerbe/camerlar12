<?php

use Livewire\Component;

new class extends Component
{
    //
    public $camer;
    public $sopie;
    public $video=null;
    public string $header = 'vidéo';
    public string $video_url='';
    public string $modalName='video-camer';
    public string $url;

    public function mount($camer = null, $sopie = null)
    {
        if ($camer && strtolower($camer['typevideo'] ?? '') === 'camer') {
            $this->video = $camer;
            $this->header = 'vidéo';
            $this->url=config('app.url')."/video/camer";
        } elseif ($sopie) {
            $this->video = $sopie;
            $this->header = 'vidéo de la semaine';
            $this->modalName = 'video-sopie';
            $this->url=config('app.url')."/video/sopie";
        }
        $this->video_url=$this->video['video_url'];
    }
};
?>

<div>


    <div class="bg-gray-900 text-white p-6 rounded-xl">
        <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2 mb-3">
            <h2 class="text-xl font-extrabold font-heading uppercase tracking-wide">
                {{$this->header}}
            </h2>
            <a href="{{$this->url}}">
                <span class="text-xs font-semibold text-brand-500">Toutes les vidéos</span>
            </a>

        </div>
        <p class="text-xs text-white mb-3 md:text-md font-medium">
            {{$this->video['titre']}}
        </p>
        <div class="block w-full">

            <flux:modal.trigger name="{{$this->modalName}}">
                <button type="button" class="relative group cursor-pointer w-full block overflow-hidden rounded-lg focus:outline-none">

                    <!-- Image qui prend toute la largeur et garde son ratio naturel -->
                    <img src="{{$this->video['cover_image']}}"
                         alt="{{$this->video['titre']}}"
                         class="w-full h-auto block object-cover transition-opacity duration-300 group-hover:opacity-90" />

                    <!-- Icône Play centrée -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-black/50 group-hover:bg-brand-500 text-white p-4 rounded-full transition-all duration-300 transform group-hover:scale-110 shadow-lg">
                            <svg class="w-8 h-8 fill-current translate-x-0.5" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>

                </button>
            </flux:modal.trigger>

        </div>
    </div>

    <flux:modal name="{{$this->modalName}}" class="md:w-[640px]">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">{{$this->video['titre']}}</flux:heading>

            </div>

            <!-- Conteneur responsive (Ratio 16:9) -->
            <div class="aspect-video w-full overflow-hidden rounded-lg">
                <iframe
                    class="w-full h-full"
                    src="{{$this->video_url}}"
                    title="Lecteur Vidéo YouTube"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen>
                </iframe>
            </div>

            <!-- Bouton de fermeture -->
            <div class="flex justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Fermer</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>
