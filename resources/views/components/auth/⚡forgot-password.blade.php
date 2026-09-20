<?php

use Livewire\Component;
use Illuminate\Support\Facades\Password;
new class extends Component
{
    //
    public string $email = '';

    public ?string $status = null;
    public function sendResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = __($status);

            return;
        }

        $this->addError('email', __($status));
    }
};
?>

<div>
    <div class="space-y-6">
        <h2 class="text-xl font-bold text-gray-800 text-center border-b pb-3">
            Mot de passe oublié
        </h2>

        @if ($status)
            <div class="bg-green-50 border-l-4 border-green-400 p-4">
                <p class="text-sm text-green-700">{{ $status }}</p>
            </div>
        @endif

        <form wire:submit="sendResetLink" class="space-y-4">
            <flux:input type="email" label="Email" wire:model="email" />
            <flux:button type="submit" variant="primary" class="w-full">
                Envoyer le lien de réinitialisation
            </flux:button>
        </form>
    </div>
</div>
