<?php

use Livewire\Component;
use App\Services\ArticleService;
use Flux\Flux;
use \Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

new class extends Component
{
    //
    public $perPage=20;
    use WithPagination;

    #[Computed]
    public function articles()
    {
        // On récupère le service via l'injection de dépendances ou app()
        $articleService = app(ArticleService::class);
        //dd($videoService->indexPaginated($this->perPage)) ;
        return $articleService->getArticleByUser(auth()->id(),$this->perPage);
    }

    public function delete($id, ArticleService $articleService)
    {

        $result=$articleService->delete($id);
        //$videos = $articleService->indexPaginated($this->perPage);
        /*if ($videos->isEmpty() && $this->getPage() > 1) {
            $this->previousPage();
        }*/

        Flux::toast(
            heading: 'Suppression',
            text: 'Article supprimé avec succès !',
            variant: 'success',
        );
        //return $this->redirect(route('admin.video.index'), navigate: true);

    }
};
?>

<div>
    <flux:card class="w-full mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Articles</flux:heading>

            </div>

            <a href="{{route('admin.article.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6" >
            <flux:table.columns sticky >
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Titre</flux:table.column>
                <flux:table.column>Rubrique</flux:table.column>
                <flux:table.column>Date</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($this->articles as $article)
                    <flux:table.row :key="$article->idarticle">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ Str::limit($article->titre,40)  }}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $article->sousrubrique->sousrubrique}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ Carbon::parse($article->dateparution)->locale('fr')->translatedFormat('d M Y H:i')  }}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.article.edit',$article->idarticle )">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $article->idarticle }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet article ?"
                                >

                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
        {{-- Pagination --}}
        <div class="mt-6">
            <flux:pagination :paginator="$this->articles" />
        </div>
    </flux:card>
</div>
