<?php

use Livewire\Component;
use Flux\Flux;
use Livewire\Attributes\Validate;
use App\Services\UserService;
new class extends Component
{
    //
    #[Validate('required', message: "L'email  est requis.")]
    #[Validate('email', message: "L'email  n'est pas valide.")]
    public string $email = '';
    #[Validate('required', message: "Le mot de passe  est requis.")]
    #[Validate('min:8', message: "Le mot de passe doit avoir une longueur d'au moins 8  caractères.")]
    public string $password = '';
    #[Validate('required', message: "La confirmation est requise")]
    #[Validate('same:password', message: "Mots de passe distincts")]
    public string $confirm_password = '';

    public function save(UserService $userService){
        $validated=$this->validate();
        try{
            $userService->firstLogin([
                'email'=>$validated['email'],
                'password'=>$validated['password'],
            ]);
            Flux::toast(
                heading: 'Activation',
                text: 'Le compte a été activé avec succès !',
                variant: 'success',
            );
            return $this->redirectRoute('login',navigate: true);
        }
        catch (\Throwable $e){

            Flux::toast(
                heading: 'Erreur',
                text: "Erreur survenue lors de l'activation du compte !",
                variant: 'danger',
            );
        }
    }
};
?>

<div>
    <div class="min-h-screen bg-slate-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">

        <!-- En-tête / Branding Camer.be -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <!-- Logo ou Titre aux couleurs du Cameroun (Vert, Rouge, Jaune) -->
            <a href="/" class="inline-block text-4xl font-extrabold tracking-tight">
                <span class="text-emerald-700">camer</span><span class="text-red-600">.be</span>
            </a>
            <p class="mt-2 text-xs text-gray-500 font-semibold uppercase tracking-widest">
                L'information du Cameroun et de sa diaspora
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Barre tricolore décorative -->
            <div class="h-1.5 w-full flex rounded-t-lg overflow-hidden">
                <div class="w-1/3 bg-emerald-600"></div>
                <div class="w-1/3 bg-red-600"></div>
                <div class="w-1/3 bg-amber-400"></div>
            </div>

            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-b-lg sm:px-10 border-t-0 border-gray-200">

                <div class="space-y-6">
                    <form wire:submit.prevent="save" class="space-y-6">
                        @csrf
                        <h2 class="text-xl font-bold text-gray-800 text-center border-b pb-3">
                            Activation du compte
                        </h2>

                        <!-- Email -->

                            <flux:input
                                type="input"
                                size="sm"
                                wire:model.defer="email" label="Email"
                            />


                        <!-- Mot de passe -->

                            <flux:input type="password" size="sm" wire:model.defer="password" label="Mot de passe"/>


                            <flux:input type="password" size="sm" wire:model.defer="confirm_password" label="Confirmation"/>


                        <div>
                            <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-700 hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150 ease-in-out">
                                <span wire:loading.remove wire:target="save">Activer</span>
                                <span wire:loading wire:target="save">Enregistrement en cours...</span>
                            </button>
                        </div>
                    </form>
                </div>
                    <!-- FORMULAIRE DE CONNEXION -->




            </div>

            <!-- Pied de page -->
            <div class="mt-6 text-center text-xs text-gray-500">
                &copy; 2005 - {{ date('Y') }} Camer.be. Tous droits réservés.
            </div>
        </div>

    </div>
</div>
