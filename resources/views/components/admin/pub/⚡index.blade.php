<?php

use Livewire\Component;
use App\Services\PubService;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Flux\Flux;

new class extends Component
{
    //


    #[Computed]
    public function pubs(){
        $pubService = app(PubService::class);
        return $pubService->index();

    }

    public function delete($id, PubService $pubService)
    {

        $result=$pubService->delete($id);
        //$this->pubs=$pubService->index();

        Flux::toast(
            heading: 'Suppression',
            text: 'Publicité supprimée avec succès !',
            variant: 'success',
        );
        return $this->redirect(route('admin.pub.index'), navigate: true);

    }
};
?>

<div>
    <flux:card class="w-full mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Publicités</flux:heading>

            </div>

            <a href="{{route('admin.pub.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6" >
            <flux:table.columns sticky >
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Dimension</flux:table.column>
                <flux:table.column>Type de Pub</flux:table.column>
                <flux:table.column>Editeur</flux:table.column>
                <flux:table.column>Fin</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($this->pubs as $pub)
                    <flux:table.row :key="$pub->idpub">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $pub->dimensions->dimension}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $pub->typepubs->pubtype}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ Str::title($pub->editor)}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ Carbon::parse($pub->endpubdate)->locale('fr')->translatedFormat('d M Y')}}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.pub.edit',$pub->idpub)">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $pub->idpub }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette publicité ?"
                                >
                                    Supprimer
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
