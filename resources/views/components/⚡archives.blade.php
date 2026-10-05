<?php

use Livewire\Component;
use App\Helpers\Helper;
use Carbon\Carbon;

new class extends Component
{
    // Composant purement affichage (aucune action Livewire) :
    // tout en protected => rien n'est sérialisé dans le snapshot.

    protected array $week  = [];
    protected array $month = [];
    protected array $year  = [];

    // Libellé, format de date et bordure de chaque colonne.
    // Les 3 colonnes étant visuellement identiques, la vue n'a
    // plus qu'une seule boucle au lieu de 3 copies du même HTML.
    protected array $periods;

    public function mount($archives)
    {
        $this->week  = $archives['week']  ?? [];
        $this->month = $archives['month'] ?? [];
        $this->year  = $archives['year']  ?? [];

        $this->periods = [
            'week'  => ['label' => 'il y a une semaine', 'format' => 'dddd D MMMM YYYY', 'border' => 'border-accent-500'],
            'month' => ['label' => 'il y a un mois',     'format' => 'D MMMM YYYY',      'border' => 'border-accent-500'],
            'year'  => ['label' => 'il y a un an',       'format' => 'D MMMM YYYY',      'border' => 'border-highlight-500'],
        ];
    }
};
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

        @foreach($this->periods as $key => $config)
            @php
                $articles = $this->{$key};
            @endphp

            @if(count($articles) > 0)
                <div class="bg-gray-700 rounded-xl p-3">
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 {{ $config['border'] }} pl-2">
                        {{ $config['label'] }}
                    </h4>

                    <div class="flex flex-col gap-4">
                        @foreach($articles as $article)
                            @php
                                $titre = $article['titre'];
                                $img = $article['image_url'];

                                $url = Helper::makeUrl(
                                    $article['rubrique']['rubrique'],
                                    $article['sousrubrique']['sousrubrique'],
                                    $article['slug']
                                );
                                $dateparution = ucfirst(
                                    Carbon::parse($article['dateparution'])
                                        ->locale('fr')
                                        ->isoFormat($config['format'])
                                );
                                $hits = $article['hit'];
                            @endphp

                            <div class="flex items-start gap-3">
                                <img src="{{ $img }}" alt="{{ $titre }}"
                                     class="md:w-15 md:h-15 w-9 h-9 rounded-full object-cover shrink-0">

                                <div class="flex-1 rounded-lg px-4 py-3">
                                    <a href="/{{ $url }}" class="block w-full group">
                                        <p class="line-clamp-2 text-sm font-medium text-gray-100 dark:text-white group-hover:underline leading-snug">{{ $titre }}</p>
                                    </a>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm text-red-700 dark:text-white">📅 {{ $dateparution }}</span>
                                        <span class="text-[11px] text-gray-400 flex">
                                            <flux:icon.eye variant="micro" class="mr-1" /> {{ $hits }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>
