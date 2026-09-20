<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Flux\Flux;
use App\Services\PubTypeService;
new class extends Component
{
    #[Validate('required', message: 'Le type de publicité est requis.')]
    public string $pubtype = '';
    //
    public function save(PubTypeService $pubTypeService){
        $validated = $this->validate();
        try{

            $result=$pubTypeService->create([
                'pubtype' => $validated['pubtype'],
            ]);

            Flux::toast(
                heading: 'Création',
                text: 'Type de publicité crée avec succès !',
                variant: 'success',
            );
            return $this->redirectRoute('admin.pubtype.index',navigate: true);
        }
        catch (\Throwable $e){
            report($e);
            Flux::toast(
                heading: 'Erreur',
                text: "Erreur survenue lors de la création d'un PubType !",
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
                Type de publicité
            </flux:heading>
            <a href="{{route('admin.pubtype.index')}}"
               class="ml-auto bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Liste</span>
            </a>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="space-y-6">
                <flux:input
                    wire:model="pubtype"
                    type="text"
                    min="1"
                    label="Type de Publicité" size="xm" placeholder="1200" />

                <div class="flex justify-center">
                    <flux:button type="submit" class="mx-auto" icon="plus" variant="filled" color="blue">
                        Enregistrer
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:card>
</div>
