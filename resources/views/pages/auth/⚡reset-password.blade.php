<?php

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

new class extends Component
{
    public string $token='';
    public string $email='';
    public string $password='';
    public string $password_confirmation='';
    public string $status='';
    //
    public function mount(string $token){
        $this->token=$token;
        $this->email=request('email','');
    }
    public function resetPassword(){
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',

        ]);
        $this->status=Password::reset([
            'email' =>$this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ],
            function ($user){
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token'=>Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );
        if($this->status===Password::PASSWORD_RESET){
            session()->flash('status',__($this->status));
            $this->redirectRoute('login');
        }
        else{
            $this->addError('email',__($this->status));
        }
    }
};
?>

<div>
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-gray-800 text-center border-b pb-3">
            Réinitialisation du mot de passe
        </h2>

        @if ($this->status)
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <p class="text-sm text-green-700">{{ $this->status }}</p>
            </div>
        @endif

        <form wire:submit="sendResetLink" class="space-y-4">
            <flux:input type="email" label="Email" wire:model="email" />
            <flux:input type="password" label="Mot de passe" wire:model="password" />
            <flux:input type="password" label="Confirmation" wire:model="password_confirmation" />
            <flux:button type="submit" variant="primary" class="w-full">

                Réinitialisation
            </flux:button>
        </form>
    </div>
</div>
