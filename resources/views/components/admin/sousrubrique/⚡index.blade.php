<?php

use Livewire\Component;
use App\Services\SousrubriqueService;
use Livewire\Attributes\Validate;
use Flux\Flux;

new class extends Component
{
    public $sousrubriques;


    public function mount(SousrubriqueService $sousrubriqueService){
        $this->sousrubriques=$sousrubriqueService->index();

    }

    public function delete($id, SousrubriqueService $sousrubriqueService)
    {

        $result=$sousrubriqueService->delete($id);
        $this->sousrubriques=$sousrubriqueService->index();

        Flux::toast(
            heading: 'Suppression',
            text: 'Sous Rubrique supprimée avec succès !',
            variant: 'success',
        );
        return $this->redirect(route('admin.sousrubrique.index'), navigate: true);

    }
};
?>

<div>
    <flux:card class="w-full mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Sous Rubriques</flux:heading>

            </div>

            <a href="{{route('admin.sousrubrique.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6" >
            <flux:table.columns sticky >
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Sous Rubrique</flux:table.column>
                <flux:table.column>Rubrique</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($this->sousrubriques as $sousrubrique)
                    <flux:table.row :key="$sousrubrique->idsousrubrique">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $sousrubrique->sousrubrique}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $sousrubrique->rubrique}}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.sousrubrique.edit',$sousrubrique->idsousrubrique)">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $sousrubrique->idsousrubrique }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette sous rubrique ?"
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
