<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Flux\Flux;
use App\Services\RubriqueService;

new class extends Component
{
    //
    //public $rubrique;
    public $id;

    #[Validate('required', message: 'La rubrique est requise')]
    public string $rubrique = '';

    public $rubriqueId;
    public $item;

    public function mount(RubriqueService $rubriqueService)
    {
        $this->item= $rubriqueService->findById($this->id);



        $this->rubriqueId = $this->item->idrubrique;
        $this->rubrique = $this->item->rubrique;
    }

    public function save(RubriqueService $rubriqueService){
        $validated = $this->validate();
        $rubrique=[
            'rubrique'=>$this->rubrique,
            'rubriqueid'=>$this->rubriqueId,
        ];
        //dd($pubdimension);
        $rubriqueService->update($rubrique,$this->id);

        Flux::toast(
            heading: 'Mise à jour',
            text: 'Rubrique mise à jour avec succès !',
            variant: 'success',
        );
        return $this->redirectRoute('admin.rubrique.index',navigate: true);
    }
};
?>

<div>
    <flux:card class="space-y-6 w-1/2  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-5">
            <flux:icon name="pencil" class="size-5" />
            <flux:heading size="lg" class="font-bold border-b-2 uppercase">
                Rubriques
            </flux:heading>
            <a href="{{route('admin.rubrique.index')}}"
               class="ml-auto bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Liste</span>
            </a>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="space-y-6">
                <flux:input wire:model="rubrique"  label="Rubrique" size="xm" placeholder="1200" />

                <div class="flex justify-center">
                    <flux:button type="submit" class="mx-auto" icon="plus" variant="filled" color="blue">
                        Mettre à jour
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:card>
</div>
