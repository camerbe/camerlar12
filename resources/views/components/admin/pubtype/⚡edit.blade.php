<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Flux\Flux;
use App\Services\PubTypeService;
new class extends Component
{
    //
    public $id;

    #[Validate('required', message: 'Le type de publicité est requis')]
    public string $pubtype = '';

    public $pubtypeId;
    public $item;

    public function mount(PubTypeService $pubTypeService)
    {
        $this->item= $pubTypeService->findById($this->id);



        $this->pubtypeId = $this->item->idpubtype;
        $this->pubtype = $this->item->pubtype;
    }

    public function save(PubTypeService $pubTypeService){
        $validated = $this->validate();
        $rubrique=[
            'pubtype'=>$this->pubtype,
            'pubtypeid'=>$this->pubtypeId,
        ];

        $pubTypeService->update($rubrique,$this->id);

        Flux::toast(
            heading: 'Mise à jour',
            text: 'Type de publicité mis à jour avec succès !',
            variant: 'success',
        );
        return $this->redirectRoute('admin.pubtype.index',navigate: true);
    }
};
?>

<div>
    <flux:card class="space-y-6 w-8/12  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-5">
            <flux:icon name="pencil" class="size-5" />
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
                <flux:input wire:model="pubtype"  label="Type de publicité" size="xm" placeholder="1200" />
                <div>
                    @error('pubtype') <span class="error">{{ $message }}</span> @enderror
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
