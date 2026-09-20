<?php

use Livewire\Component;
use App\Services\ArticleService;
use Flux\Flux;

new class extends Component
{
    public string $search = '';
    public $result = null;
    //public string $search = '';

    //
    public function mount()
    {
        //$this->result = app(ArticleService::class)->search($this->q);
        //dd($this->result);
    }
    public function doSearch(){
        if (empty(trim($this->search))) {
            return;
        }
        Flux::toast(
            heading: 'Recherche',
            text: 'Article(s) trouvé(s) !',
            variant: 'success',
        );
        return $this->redirectRoute('admin.article.search', ['search' => $this->search],navigate: true);
    }
};
?>

<div>
    <form  wire:submit.prevent="doSearch">
       <input
           type="input"
           wire:model.live.debounce.300ms="search"
           wire:keydown.enter="doSearch"
           placeholder="Rechercher un article, un auteur..."
           class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-green-700"
       />
    </form>

</div>
