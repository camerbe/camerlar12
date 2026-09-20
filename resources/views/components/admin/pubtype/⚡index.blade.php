<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Flux\Flux;
use App\Services\PubTypeService;

new class extends Component
{
    //
    public $pubtypes;


    public function mount(PubTypeService $pubTypeService){
        $this->pubtypes=$pubTypeService->getAll();
    }

    public function delete($id, PubTypeService $pubTypeService)
    {
        //$pubdimension = PubDimension::find($id);
        $result=$pubTypeService->delete($id);
        $this->pubtypes=$pubTypeService->getAll();

        Flux::toast(
            heading: 'Suppression',
            text: 'Type de publicité supprimé avec succès !',
            variant: 'success',
        );
        return $this->redirect(route('admin.pubtype.index'), navigate: true);

    }
};
?>

<div>
    <flux:card class="w-9/12 mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Type de publicité</flux:heading>

            </div>

            <a href="{{route('admin.pubtype.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6">
            <flux:table.columns>
                <flux:table.column>#</flux:table.column>
                <flux:table.column>PubType</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($pubtypes as $pubtype)
                    <flux:table.row :key="$pubtype->idpubtype">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $pubtype->pubtype}}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.pubtype.edit',$pubtype->idpubtype)">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $pubtype->idpubtype }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce type de publicité ?"
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
