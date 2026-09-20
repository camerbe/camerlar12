<?php

use Livewire\Component;
use \App\Http\Controllers\api\V1\AuthController;
use Illuminate\Http\Request;
use \App\Models\User;
use Illuminate\Support\Facades\Auth;
use Flux\Flux;
use Livewire\Attributes\Validate;

new class extends Component
{
    public $isForgotPassword = false;
    protected $authController;
    protected $apiResponseArray=[];
    #[Validate('required', message: "L'email  est requis.")]
    #[Validate('email', message: "L'email  n'est pas valide.")]
    public string $email = '';
    public string $apiMessage = '';
    public bool $remember = false;
    protected $isSuccess=false;
    #[Validate('required', message: "Le mot de passe  est requis.")]
    public string $password = '';


    public function login(){
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $request = Request::create('/login', 'POST', [
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember ?? false,
        ]);

        $response = app(AuthController::class)->login($request);
        $array = json_decode($response->getContent(), true);
        $this->apiResponseArray=json_decode($response->getContent(), true);
        $this->isSuccess=$this->apiResponseArray['success'];
        $this->apiMessage=$this->apiResponseArray['message'];
        if ($this->isSuccess) {
            $token = $this->apiResponseArray['token'] ?? null;
            if (!$token) {
                $this->isSuccess = false;

                $this->apiResponseArray['message'] =
                    'Token d’authentification absent.';

                return;
            }
            session()->put('api_token', $token);
            $user = User::where('email', $this->email)->first();
            if (!$user) {
                $this->isSuccess = false;
                $this->apiResponseArray['message'] =
                    'Utilisateur introuvable.';

                return;
            }
            Auth::guard('web')->login($user, $this->remember ?? false);
            //session()->regenerate();
            $cookies=cookie(
                'jwt_token',
                $token,
                60,
                '/',
                null,
                request()->secure(),
                true,
                false,
                'lax'
            );
            \Cookie::queue($cookies);
            session()->flash('user', $user);
            return $this->redirect(route('admin.index'));
        }
        // Connexion échouée
        $this->isSuccess = false;

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

                @if(!$isForgotPassword)
                    <!-- FORMULAIRE DE CONNEXION -->
                    <form wire:submit.prevent="login" class="space-y-6">
                        @csrf
                        <h2 class="text-xl font-bold text-gray-800 text-center border-b pb-3">
                            Espace Membre
                        </h2>

                        <!-- Email -->
                        <div>
                            <flux:input type="input" wire:model.defer="email" label="Email"/>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <flux:input type="password" wire:model.defer="password" label="Mot de passe"/>
                        </div>

                        <!-- Se souvenir de moi & Mot de passe oublié -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input wire:model="remember" id="remember" type="checkbox"
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-900">
                                    Se souvenir de moi
                                </label>
                            </div>

                            <div class="text-sm">
                                <flux:modal.trigger name="ForgotPassword">
                                   <flux:button type="button" class="font-medium text-red-600 hover:text-red-500 focus:outline-none underline">Mot de passe oublié</flux:button>
                                </flux:modal.trigger>
                            </div>
                        </div>
                        @if(!$this->isSuccess)
                            <span class="flex items-center text-red-600 text-xs mt-1 block">{{ $this->apiMessage }}</span>
                        @endif
                        <!-- Bouton Se connecter -->
                        <div>
                            <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-700 hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150 ease-in-out">
                                <span wire:loading.remove wire:target="login">Se connecter</span>
                                <span wire:loading wire:target="login">Connexion en cours...</span>
                            </button>
                        </div>
                    </form>

                @else
                    <!-- FORMULAIRE MOT DE PASSE OUBLIÉ -->
                    <form wire:submit.prevent="sendResetLink" class="space-y-6">
                        <h2 class="text-xl font-bold text-gray-800 text-center border-b pb-3">
                            Récupération de mot de passe
                        </h2>

                        @if($statusMessage)
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                                <p class="text-sm text-green-700">{{ $statusMessage }}</p>
                            </div>
                        @endif


                    </form>
                @endif

            </div>

            <!-- Pied de page -->
            <div class="mt-6 text-center text-xs text-gray-500">
                &copy; 2005 - {{ date('Y') }} Camer.be. Tous droits réservés.
            </div>
        </div>
        <flux:modal name="ForgotPassword" class="md:w-96">
            <livewire:pages::auth.forgot-password/>
        </flux:modal>
    </div>
</div>
