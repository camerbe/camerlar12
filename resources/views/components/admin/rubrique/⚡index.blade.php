<?php

use Livewire\Component;
use App\Services\RubriqueService;
use Flux\Flux;
use \Livewire\WithPagination;

new class extends Component
{
    //
    public $rubriques;


    public function mount(RubriqueService $rubriqueService){
        $this->rubriques=$rubriqueService->index(10);

    }

    public function delete($id, RubriqueService $rubriqueService)
    {

        $result=$rubriqueService->delete($id);
        //$this->rubriques=$rubriqueService->index();

        Flux::toast(
            heading: 'Suppression',
            text: 'Rubrique supprimée avec succès !',
            variant: 'success',
        );
        //return $this->redirect(route('admin.rubrique.index'), navigate: true);

    }
};
?>

<div>
    <flux:card class="w-9/12 mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Rubriques</flux:heading>

            </div>

            <a href="{{route('admin.rubrique.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6" >
            <flux:table.columns sticky >
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Rubrique</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($rubriques as $rubrique)
                    <flux:table.row :key="$rubrique->idrubrique">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $rubrique->rubrique}}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.rubrique.edit',$rubrique->idrubrique)">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $rubrique->idrubrique }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette rubrique ?"
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
