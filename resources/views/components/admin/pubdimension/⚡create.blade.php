<?php

use Livewire\Component;
use \App\Services\PubDimensionService;
use Livewire\Attributes\Validate;
use Flux\Flux;

new class extends Component
{
    #[Validate('integer', message: 'La dimension doit être un nombre entier positif.')]
    public ?int $dimension = null;

    //
    public function save(PubDimensionService $pubdimensionService){
        $validated = $this->validate();
        try{

            $result=$pubdimensionService->create([
                'dimension' => (int) $validated['dimension'],
            ]);

            Flux::toast(
                heading: 'Création',
                text: 'Dimension créée avec succès !',
                variant: 'success',
            );
            return $this->redirectRoute('admin.pubdimension.index',navigate: true);
        }
        catch (\Throwable $e){
            Flux::toast(
                heading: 'Erreur',
                text: "Erreur survenue lors de la création d'une Dimension !",
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
                Dimension
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
            <flux:input
                wire:model.number="dimension"
                type="number"
                min="1"
                label="Dimension" size="xm" placeholder="1200" />

                <div class="flex justify-center">
                    <flux:button type="submit" class="mx-auto" icon="plus" variant="filled" color="blue">
                        Enregistrer
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:card>
</div>
