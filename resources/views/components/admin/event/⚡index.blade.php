<?php

use Livewire\Component;

use App\Services\EvenementService;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

new class extends Component
{
    //
    #[Computed]
    public function events()
    {
        // On récupère le service via l'injection de dépendances ou app()
        $eventService = app(EvenementService::class);
        //dd($videoService->indexPaginated($this->perPage)) ;
        return $eventService->index();
    }

    public function delete($id, EvenementService $eventService)
    {

        $result=$eventService->delete($id);

        Flux::toast(
            heading: 'Suppression',
            text: 'Évènement supprimé avec succès !',
            variant: 'success',
        );
        //return $this->redirect(route('admin.video.index'), navigate: true);

    }
};
?>

<div>
    <flux:card class="w-2/3 mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Évènements</flux:heading>

            </div>

            <a href="{{route('admin.event.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6" >
            <flux:table.columns sticky >
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Date</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($this->events as $event)
                    <flux:table.row :key="$event->idevent">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ Carbon::parse($event->eventdate )->locale('fr')->translatedFormat('d M Y')  }}</flux:table.cell>

                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.event.edit',$event->idevent)">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $event->idevent }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet évènement ?"
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
