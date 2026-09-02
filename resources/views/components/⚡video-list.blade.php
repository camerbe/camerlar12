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
    public array $feedVideos = [];
    public $perPage=10;
    public $hasMore=true;
    public $mostReaded;
    public $sopie;
    public $camer;
    public $debat;
    public $droit;
    public $skypper;
    public string $cacheKey;
    public ?array $selectedVideo = null;
    public ?string $selectedVideoUrl = null;
    public bool $showVideoModal = false;

    public function mount($videos){
        $videos = is_array($videos) ? $videos : [];
        if (empty($videos)) {
            $this->hasMore = false;
            return;
        }
        $typeVideo = $videos[0]['typevideo'] ?? 'videos';
        $this->cacheKey = 'video-feed-' . md5($typeVideo);
        //$this->rubriqueArticles=$rubriqueArticles;
        Cache::put($this->cacheKey, $videos, now()->addMinutes(10));
        $this->updateFeed($videos);

    }
    public function selectVideo($id): void
    {
        $idVideo=(int)$id;
        $videos = Cache::get($this->cacheKey, []);
        //$this->selectedVideo = collect($this->videos)->firstWhere('id', $id);
        $video = collect($videos)
            ->firstWhere('id', $idVideo);


        if (!$video) {
            $this->selectedVideo = null;
            $this->selectedVideoUrl = null;
            $this->showVideoModal = false;
            return;
        }

        $this->selectedVideo = $video;
        $this->selectedVideoUrl =$video["video_url"];
        $this->showVideoModal = true;
        $this->dispatch('video-ready');
    }
    public function updatedShowVideoModal($value): void
    {
        // Si la modale vient de se fermer
        if (!$value) {
            $this->closeVideoModal();
        }
    }

    public function closeVideoModal(): void
    {
        $this->selectedVideo = null;
        $this->selectedVideoUrl = null;
        $this->showVideoModal = false;
    }
    public function loadMore(){

        $this->perPage += 6;
        $full = Cache::get($this->cacheKey, []);
        $this->updateFeed($full);
    }
    private function updateFeed($collection)
    {
        $feedSource = array_slice($collection, 0);
        $this->feedVideos= array_slice($feedSource, 0, $this->perPage);
        $this->hasMore = $this->perPage < count($feedSource);
    }
};
?>
<div class="space-y-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <section>
                    <div class="flex items-center justify-between border-b-2 border-brand-500 pb-2">
                        <h2 class="text-xl font-extrabold font-heading uppercase tracking-wide">
                            Dernières vidéos de {{$this->feedVideos[0]["typevideo"]}}
                        </h2>
                        <span class="text-xs font-semibold text-brand-500">Flux en direct</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        @foreach($this->feedVideos as $video)
                            @php
                                $videoID=$video["video"];
                                $coverImage=$video["cover_image"] ;
                                $titre=$video["titre"];
                                $id= $video['id'];

                            @endphp
                            {{-- Utilisation de composants Flux UI --}}

                            <article
                                wire:key="video-{{ $id }}"
                                class="group flex flex-col bg-white dark:bg-dark-surface rounded-xl overflow-hidden border border-gray-100 dark:border-dark-border shadow-sm hover:shadow-md transition-all">

                                <button
                                    type="button"
                                    wire:click="selectVideo({{ $id }})"
                                    wire:loading.attr="disabled"
                                    class="relative group cursor-pointer w-full block overflow-hidden rounded-lg focus:outline-none">

                                    <img src="{{ $coverImage }}"
                                         alt="{{ $titre }}"
                                         class="w-full h-auto block object-cover transition-opacity duration-300 group-hover:opacity-90" />

                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="bg-black/50 group-hover:bg-brand-500 text-white p-4 rounded-full transition-all duration-300 transform group-hover:scale-110 shadow-lg">
                                            <svg class="w-8 h-8 fill-current translate-x-0.5" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </button>

                            </article>
                        @endforeach

                            <flux:modal
                                wire:model.self="showVideoModal"
                                x-on:close="$wire.closeVideoModal()"
                                name="video-player" class="md:w-[640px]">

                                @if($selectedVideo)

                                <div class="space-y-4">
                                    <div>
                                        <flux:heading size="lg">{{$selectedVideo['titre']}}</flux:heading>

                                    </div>

                                    <!-- Conteneur responsive (Ratio 16:9) -->
                                    <div class="aspect-video w-full overflow-hidden rounded-lg">
                                        <iframe
                                            class="w-full h-full"
                                            src="{{ $selectedVideoUrl}}"
                                            title="{{$selectedVideo['titre']}}"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            referrerpolicy="strict-origin-when-cross-origin"
                                            allowfullscreen>
                                        </iframe>
                                    </div>

                                    <!-- Bouton de fermeture -->
                                    <div class="flex justify-end">
                                        <flux:modal.close>
                                            <flux:button variant="ghost" wire:click="closeVideoModal">Fermer</flux:button>
                                        </flux:modal.close>
                                    </div>
                                </div>
                                @endif
                            </flux:modal>
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
                                <span wire:loading.remove wire:target="loadMore">Charger plus de vidéos</span>
                                <span wire:loading wire:target="loadMore">Chargement...</span>
                            </button>
                        </div>
                    @endif
                </section>

            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-6">
                {{-- 4. Sous-composant Livewire pour la Sidebar--}}

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

