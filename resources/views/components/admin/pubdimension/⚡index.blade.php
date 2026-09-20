<?php

use Livewire\Component;
use App\Services\PubDimensionService;
use Flux\Flux;

new class extends Component
{
    //
    public $pubdimensions;


    public function mount(PubDimensionService $pubdimensionService){
        $this->pubdimensions=$pubdimensionService->index();
    }

    public function delete($id, PubDimensionService $pubdimensionService)
    {
        //$pubdimension = PubDimension::find($id);
        $result=$pubdimensionService->delete($id);
        $this->pubdimensions=$pubdimensionService->index();
        //session()->flash('success', 'Dimension supprimée avec succès !');
        //Flux::toast('Dimension supprimée avec succès !');
        Flux::toast(
            heading: 'Suppression',
            text: 'Dimension supprimée avec succès !',
            variant: 'success',
        );
        return $this->redirect(route('admin.pubdimension.index'), navigate: true);

    }


};
?>

<div>

    <flux:card class="w-1/2 mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Dimensions</flux:heading>

            </div>

            <a href="{{route('admin.pubdimension.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6">
            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Dimension</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($pubdimensions as $pubdimension)
                    <flux:table.row :key="$pubdimension->idpubdimension">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $pubdimension->dimension}}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.pubdimension.edit',$pubdimension->idpubdimension)">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $pubdimension->idpubdimension }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette dimension ?"
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
