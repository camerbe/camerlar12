<?php

use Livewire\Component;
use App\Services\RubriqueService;
use Livewire\Attributes\Validate;
use Flux\Flux;

new class extends Component
{
    //
    #[Validate('required', message: 'La rubrique est requise.')]
    public $rubrique = '';

    //
    public function save(RubriqueService $rubriqueService){
        $validated = $this->validate();
        try{

            $result=$rubriqueService->create([
                'rubrique' =>  $validated['rubrique'],
            ]);

            Flux::toast(
                heading: 'Création',
                text: 'Rubrique créée avec succès !',
                variant: 'success',
            );
            return $this->redirectRoute('admin.rubrique.index',navigate: true);
        }
        catch (\Throwable $e){
            Flux::toast(
                heading: 'Erreur',
                text: "Erreur survenue lors de la création d'une Rubrique !",
                variant: 'danger',
            );
        }

    }
};
?>

<div>
    <flux:card class="space-y-6 w-1/2  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-5">
            <flux:icon name="plus" class="size-5" />
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
                <flux:input
                    wire:model="rubrique"
                    type="text"
                    min="1"
                    label="Rubrique" size="xm" placeholder="Rubrique" />

                <div class="flex justify-center">
                    <flux:button type="submit" class="mx-auto" icon="plus" variant="filled" color="blue">
                        Enregistrer
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:card>
</div>
