<?php

use Livewire\Component;

new class extends Component
{
    //
    public array $articles = [];

    /*public function render()
    {
        return view('livewire.sidebar-news');
    }*/
};
?>

<?php

new class extends Component
{
    //
};
?>

<div class="bg-white dark:bg-dark-surface p-6 rounded-xl border border-gray-100 dark:border-dark-border shadow-sm">
    <h3 class="text-lg font-bold font-heading mb-4 border-l-4 border-highlight-500 pl-3">
        🔥 Les plus lus
    </h3>
    <div class="space-y-4">
        @foreach($articles as $article)
            <a href="#" class="flex items-start space-x-3 group border-b border-gray-100 dark:border-gray-800 pb-3">
                <span class="text-2xl font-black text-gray-300 dark:text-gray-600 group-hover:text-brand-500 transition">{{$loop->iteration}}</span>
                <div>
                    <h4 class="text-xs font-bold leading-snug text-gray-800 dark:text-gray-200 group-hover:text-brand-500 transition line-clamp-2">
                        {{$article['titre']}}
                    </h4>
                    <span class="text-[10px] text-gray-400 mt-1 block">18.2k vues</span>
                </div>
            </a>
        @endforeach

    </div>
</div>


