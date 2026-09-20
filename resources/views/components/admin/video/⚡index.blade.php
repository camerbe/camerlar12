<?php

use Livewire\Component;

use App\Services\VideoService;
use Flux\Flux;
use \Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;

new class extends Component
{
    //
    //public $videos;
    public $perPage=10;
    use WithPagination;

    #[Computed]
    public function videos()
    {
        // On récupère le service via l'injection de dépendances ou app()
        $videoService = app(VideoService::class);
        //dd($videoService->indexPaginated($this->perPage)) ;
        return $videoService->indexPaginated($this->perPage);
    }
    /*public function render(VideoService $videoService)
    {
       //return view('admin.video.index');
    }*/

    public function delete($id, VideoService $videoService)
    {

        $result=$videoService->delete($id);
        $videos = $videoService->indexPaginated($this->perPage);
        /*if ($videos->isEmpty() && $this->getPage() > 1) {
            $this->previousPage();
        }*/

        Flux::toast(
            heading: 'Suppression',
            text: 'Vidéo supprimée avec succès !',
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
                <flux:heading size="xl"  class="font-bold">Vidéos</flux:heading>

            </div>

            <a href="{{route('admin.video.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6" >
            <flux:table.columns sticky >
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Titre</flux:table.column>
                <flux:table.column>Type Vidéo</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($this->videos as $video)
                    <flux:table.row :key="$video->idvideo">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ Str::limit($video->titre,40)  }}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $video->typevideo}}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.video.edit',$video->idvideo )">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $video->idvideo }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette vidéo ?"
                                >
                                    Supprimer
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
        {{-- Pagination --}}
        <div class="mt-6"> <flux:pagination :paginator="$this->videos" /></div>
    </flux:card>
</div>
