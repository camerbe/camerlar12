<?php

use Livewire\Component;
use App\Services\PubDimensionService;
use Livewire\Attributes\Validate;
use Flux\Flux;

new class extends Component
{
    //
    public $pubdimension;
    public $id;

    #[Validate('required', message: 'La dimension est requise')]
    public string $dimension = '';

    public $pubdimensionId;
    public $item;

    public function mount(PubDimensionService $pubDimensionService)
    {
        $this->item= $pubDimensionService->findById($this->id);



        $this->pubdimensionId = $this->item->idpubdimension;
        $this->dimension = $this->item->dimension;
    }

    public function save(PubDimensionService $pubDimensionService){
        $validated = $this->validate();
        $pubdimension=[
            'dimension'=>$this->dimension,
            'pubdimensionid'=>$this->pubdimensionId,
        ];
        //dd($pubdimension);
        $pubDimensionService->update($pubdimension,$this->id);
        //session()->flash('success', 'Dimension mise à jour avec succès !');
        //Flux::toast('Dimension mise à jour avec succès !');
        Flux::toast(
            heading: 'Mise à jour',
            text: 'Dimension mise à jour avec succès !',
            variant: 'success',
        );
        return $this->redirectRoute('admin.pubdimension.index');
    }
};
?>

<div>

    <flux:card class="space-y-6 w-1/2  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-5">
            <flux:icon name="pencil" class="size-5" />
            <flux:heading size="lg" class="font-bold border-b-2 uppercase">
                Dimensions
            </flux:heading>
            <a href="{{route('admin.pubdimension.index')}}"
               class="ml-auto bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Liste</span>
            </a>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="space-y-6">
                <flux:input wire:model="dimension"  label="Dimension" size="xm" placeholder="1200" />
                <div>
                    @error('dimension') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-center">
                    <flux:button type="submit" class="mx-auto" icon="plus" variant="filled" color="blue">
                        Mettre à jour
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:card>
</div>
