<?php

use Livewire\Component;
use App\Services\EvenementService;
use Livewire\Attributes\Validate;
use Flux\Flux;
use Carbon\Carbon;
new class extends Component
{
    //
    public $item;
    public $id;

    #[Validate('required', message: 'La date est requise.')]
    public ?string $eventdate  = null;
    #[Validate('required', message: 'La vidéo est requise.')]
    public $affiche= '';

    public function mount(EvenementService $eventService){

        $this->item= $eventService->findById($this->id);

        $this->affiche=$this->item->affiche;
        $this->eventdate =Carbon::parse($this->item->eventdate )->format('Y-m-d')?? null;

    }

    public function save(EvenementService $eventService){
        $validated = $this->validate();
        $event=[
            'affiche'=>$this->affiche,
            'eventdate'=>$this->eventdate,

        ];

        $eventService->update($event,$this->id);

        Flux::toast(
            heading: 'Mise à jour',
            text: 'Evènement mise à jour avec succès !',
            variant: 'success',
        );
        return $this->redirectRoute('admin.event.index',navigate: true );
    }
};
?>

<div>
    <flux:card class="space-y-6 w-10/12  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-3">
            <flux:icon name="pencil" class="size-5" />
            <flux:heading size="lg" class="font-bold border-b-2 uppercase">
                Évènement
            </flux:heading>
            <a href="{{route('admin.event.index')}}"
               class="ml-auto bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Liste</span>
            </a>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="space-y-6">


                <flux:input
                    wire:model="eventdate"
                    type="date"
                    min="1"
                    label="Titre" size="xm" placeholder="Date ..." />

                <div wire:ignore>
                    <flux:textarea

                        id="affiche"
                        label="Affiche"
                        placeholder="Affiche..."
                    />
                </div>
                <div>
                    @error('affiche') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-center">
                    <flux:button type="submit" class="mx-auto" icon="plus" variant="filled" color="blue">
                        Mettre à jour
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:card>
    @assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.9.0/tinymce.min.js"></script>
    @endassets
    @script
    <script>
        function initTinyMCE() {

            const textarea = document.querySelector('#affiche');

            if (!textarea) {
                return;
            }

            // Évite une double initialisation
            if (tinymce.get('affiche')) {
                return;
            }

            tinymce.init({
                selector: '#affiche',

                relative_urls: false,
                height: 400,

                menubar: false,

                plugins: [
                    'image', 'media',

                ],

                toolbar:
                    'image media',

                // Important pour le File Manager
                file_picker_types: 'image media file',

                file_picker_callback: function(callback, value, meta) {

                    const width = window.innerWidth * 0.8;
                    const height = window.innerHeight * 0.8;

                    let cmsURL =
                        window.location.origin +
                        '/laravel-filemanager?editor=' +
                        encodeURIComponent(meta.fieldname);

                    if (meta.filetype === 'image') {
                        cmsURL += '&type=Images';
                    } else {
                        cmsURL += '&type=Files';
                    }

                    tinymce.activeEditor.windowManager.openUrl({
                        url: cmsURL,
                        title: 'File Manager',
                        width: width,
                        height: height,
                        resizable: 'yes',
                        close_previous: 'no',

                        onMessage: function(api, message) {

                            console.log('File Manager:', message);

                            if (message.content) {
                                callback(message.content);
                            }

                            api.close();
                        }
                    });
                },

                setup: function(editor) {

                    // Synchronisation TinyMCE -> Livewire
                    editor.on('change input undo redo keyup', function() {

                        $wire.set(
                            'affiche',
                            editor.getContent()
                        );
                    });

                    // Synchronisation initiale
                    editor.on('init', function() {

                        const value = $wire.get('affiche');

                        if (value) {
                            editor.setContent(value);
                        }
                    });
                }
            });
        }

        initTinyMCE();

        // Utile avec Livewire + wire:navigate
        document.addEventListener('livewire:navigated', function() {
            initTinyMCE();
        });
    </script>
    @endscript
</div>
