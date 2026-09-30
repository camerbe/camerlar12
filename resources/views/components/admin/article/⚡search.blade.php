<?php

use Livewire\Component;
use Flux\Flux;

new class extends Component
{
    public string $search = '';
    public $result = null;
    //public string $search = '';

    //

    public function doSearch(){
        if (empty(trim($this->search))) {
            return;
        }

        Flux::toast(
            heading: 'Recherche',
            text: 'Recherche en cours...',
            variant: 'success',
        );
        return $this->redirectRoute('admin.article.search', ['search' => $this->search]);
    }
};
?>

<div>
    <form  wire:submit.prevent="doSearch">
       <input
           type="search"
           wire:model.live.debounce.300ms="search"
           wire:keydown.enter="doSearch"
           placeholder="Rechercher un article, un auteur..."
           class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-green-700"
       />
    </form>

</div>
