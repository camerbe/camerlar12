<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
new class extends Component
{

    //
    public function logout(){
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return $this->redirectRoute('admin.index', navigate: true);
        //dd('LOGOUT LIVEWIRE');
    }

};
?>

<div>
    <button type="button" wire:click="logout" title="Déconnexion" class="cursor-pointer relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
        <i data-lucide="log-out" class="w-5 h-5 pointer-events-none"></i>
    </button>
</div>
