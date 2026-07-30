<?php

use Livewire\Component;
use App\Services\ArticleService;

new class extends Component
{
    public ?array $heroArticle = null;
    public array $trendingArticles = [];
    public array $sidebarArticles = [];
    public array $feedArticles = [];
    public $perPage=10;
    public $hasMore=true;

    public function mount(ArticleService $articleService){
        $rawArticles=$articleService->getArticles();
        $collection = collect($rawArticles);
        $this->heroArticle=$collection->first();
        $this->trendingArticles = $collection->slice(1, 3)->values()->toArray();
        $this->sidebarArticles = $collection->slice(4, 5)->values()->toArray();

        // - Objet 4 : Tout le reste pour le fil d'actualités principal
        $this->feedArticles = $collection->slice(6,$this->perPage)->values()->toArray();

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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($feedArticles as $item)

                        {{-- Utilisation de composants Flux UI --}}
                        <flux:card class="p-4 space-y-2">
                            <img src="{{ $item['image_url'] ?? 'https://picsum/460/300' }}" class="rounded-md aspect-video object-cover w-full">
                            <flux:heading size="sm" class="line-clamp-2">{{ $item['titre'] ?? '' }}</flux:heading>
                            <flux:subheading size="xs"></flux:subheading>
                        </flux:card>
                    @endforeach
                    <!-- Skeleton Loader pendant le chargement Livewire -->
                    <div wire:loading.grid wire:target="loadMore, setCategory" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        @for($i = 0; $i < 3; $i++)
                            <div class="bg-white dark:bg-dark-surface rounded-xl p-4 shadow-sm animate-pulse">
                                <div class="bg-gray-200 dark:bg-gray-700 h-48 rounded-lg mb-4"></div>
                                <div class="bg-gray-200 dark:bg-gray-700 h-4 w-1/3 rounded mb-2"></div>
                                <div class="bg-gray-200 dark:bg-gray-700 h-6 w-full rounded mb-2"></div>
                                <div class="bg-gray-200 dark:bg-gray-700 h-4 w-2/3 rounded"></div>
                            </div>
                        @endfor
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
                </div>
            </section>

        </div>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4 space-y-6">
            {{-- 4. Sous-composant Livewire pour la Sidebar--}}
            <livewire:sidebar-news :articles="$sidebarArticles" />

        </aside>
    </div>
</div>

