<?php

use Livewire\Component;
use App\Services\UserService;
use Livewire\Attributes\Validate;
use Flux\Flux;
new class extends Component
{
    public $item;
    public $id;

    #[Validate('required', message: "Le nom  est requis.")]
    public string $nom = '';

    #[Validate('required', message: "Le prénom est requis.")]
    public string $prenom = '';

    #[Validate('required', message: "Le mail est requis.")]
    #[Validate('email', message: "Le mail n'est pas valide.")]
    public string $email = '';

    #[Validate('required', message: "Le rôle est requis.")]
    public string $role = 'USR';
    //
    public function mount(UserService $userService){

        $user= $userService->findById($this->id);

        $this->nom=$user->nom;
        $this->prenom=$user->prenom;
        $this->email=$user->email;
        $this->role=$user->role;


    }

    public function save(UserService $userService){
        $validated = $this->validate();
        $user=[
            'nom'=>$this->nom,
            'prenom'=>$this->prenom,
            'email'=>$this->email,
            'role'=>$this->role,

        ];

        $userService->update($user,$this->id);

        Flux::toast(
            heading: 'Mise à jour',
            text: 'User mise à jour avec succès !',
            variant: 'success',
        );
        return $this->redirectRoute('admin.user.index',navigate: true );
    }
};
?>

<div>
    <flux:card class="space-y-6 w-full  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-3">
            <flux:icon name="pencil" class="size-5" />
            <flux:heading size="lg" class="font-bold border-b-2 uppercase">
                Rédacteur
            </flux:heading>
            <a href="{{route('admin.user.index')}}"
               class="ml-auto bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Liste</span>
            </a>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="space-y-6">
                <div class="flex flex-row gap-4">
                    <div class="flex-1">
                        <flux:input
                            wire:model="nom"
                            type="text"
                            min="1"
                            label="Nom" size="xm" placeholder="Nom ..." />
                    </div>
                    <div class="flex-1">
                        <flux:input
                            wire:model="prenom"
                            type="text"
                            min="1"
                            label="Prénom" size="xm" placeholder="Prénom ..." />
                    </div>


                </div>
                <div class="flex flex-row gap-4">
                    <div class="flex-1">
                        <flux:input.group>
                            <flux:input.group.prefix>@</flux:input.group.prefix>

                            <flux:input
                                wire:model="email"
                                type="text"
                                min="1"
                                size="xm"
                                placeholder="Email" />
                        </flux:input.group>
                    </div>
                    <div class="flex-1">
                        <flux:radio.group wire:model="role" label="Rôle" variant="segmented">
                            <flux:radio value="ADM" label="Administrateur" />
                            <flux:radio value="USR" label="Rédacteur"  />

                        </flux:radio.group>
                    </div>
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
