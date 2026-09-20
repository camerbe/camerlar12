<?php

use Livewire\Component;
use App\Services\SousrubriqueService;
use Livewire\Attributes\Validate;
use Flux\Flux;

new class extends Component
{
    //
    #[Validate('required', message: 'La sous rubrique est requise.')]
    public $sousrubrique = '';

    public $rubriques;
    #[Validate('required', message: 'La rubrique est requise.')]
    public ?int $fkrubrique=null;

    //
    public function mount(SousrubriqueService $sousrubriqueService){
        $this->rubriques=$sousrubriqueService->allRubrique();
    }
    public function save(SousrubriqueService $sousrubriqueService){
        $validated = $this->validate();
        try{

            $result=$sousrubriqueService->create([
                'sousrubrique' =>  $validated['sousrubrique'],
                'fkrubrique' =>  $validated['fkrubrique'],
            ]);

            Flux::toast(
                heading: 'Création',
                text: 'Sous Rubrique créée avec succès !',
                variant: 'success',
            );
            return $this->redirectRoute('admin.sousrubrique.index',navigate: true);
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
                Sous Rubriques
            </flux:heading>
            <a href="{{route('admin.sousrubrique.index')}}"
               class="ml-auto bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Liste</span>
            </a>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="space-y-6">
                <flux:input
                    wire:model="sousrubrique"
                    type="text"
                    min="1"
                    label="Sous Rubrique" size="xm" placeholder="Sous Rubrique..." />

                <div>
                    <flux:select wire:model="fkrubrique" placeholder="Choix de la rubrique...">
                        @foreach($this->rubriques as $rubrique)
                            <flux:select.option value="{{$rubrique->idrubrique}}">{{$rubrique->rubrique}}</flux:select.option>
                        @endforeach


                    </flux:select>
                </div>
                <div class="flex justify-center">
                    <flux:button type="submit" class="mx-auto" icon="plus" variant="filled" color="blue">
                        Enregistrer
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:card>
</div>
