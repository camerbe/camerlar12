<?php

use Livewire\Component;
use App\Services\UserService;
use Flux\Flux;
use Livewire\Attributes\Computed;

new class extends Component
{
    //
    #[Computed]
    public function users()
    {
        // On récupère le service via l'injection de dépendances ou app()
        $userService = app(UserService::class);
        return $userService->index();
    }
};
?>

<div>
    <flux:card class="w-full mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Rédacteurs</flux:heading>

            </div>

            <a href="{{route('admin.user.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6" >
            <flux:table.columns sticky >
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Rédacteur</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Actif</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($this->users as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $user->nom  }} {{ $user->prenom  }}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $user->email  }}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            @if($user->active)
                                <i data-lucide="check" class="w-5 h-5 flex-shrink-0 text-green-500"></i>
                            @else
                                <i data-lucide="x" class="w-5 h-5 flex-shrink-0 text-red-500"></i>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.user.edit',$user->id)">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $user->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet rédacteur?"
                                >
                                    Supprimer
                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

    </flux:card>
</div>
