<?php

use Livewire\Component;
use App\Services\VideoService;
use Livewire\Attributes\Validate;
use Flux\Flux;
use Livewire\Attributes\Computed;

new class extends Component
{
    //
    public $id;

    #[Validate('required', message: 'Le titre est requis.')]
    #[Validate('max:100', message: 'Le titre ne peut excéder 100 caractères.')]
    public $titre = '';
    #[Validate('required', message: 'La vidéo est requise.')]
    public $video = '';
    #[Validate('required', message: 'Le type de vidéo est requis.')]
    #[Validate('in:Camer,Sopie', message: 'Le type de vidéo doit être Camer ou Sopie.')]
    public $typevideo = '';




    public function mount(VideoService $videoService)
    {
        $item= $videoService->findById($this->id);
        $videoId = $item->video ?? '';
        if ($videoId)
        {
            // Si ce n'est pas déjà un iframe, on le formate
            if (!str_contains($videoId, '<iframe'))
            {
                $this->video = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $videoId . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            }
            else
            {
                $this->video = $videoId;
            }
        }
        else {
            $this->video = '';
        }


        $this->titre = $item->titre;
        //$this->video = $videoId ? "https://www.youtube.com/watch?v={$videoId}" : '';
        $this->typevideo = $item->typevideo;
    }
    public function save(VideoService $videoService){
        $validated = $this->validate();
        $video=[
            'titre'=>$this->titre,
            'video'=>$this->video,
            'typevideo'=>$this->typevideo,
        ];
        //dd($pubdimension);
        $videoService->update($video,$this->id);

        Flux::toast(
            heading: 'Mise à jour',
            text: 'Vidéo mise à jour avec succès !',
            variant: 'success',
        );
        return $this->redirectRoute('admin.video.index',navigate: true);
    }
};
?>

<div>
    <flux:card class="space-y-6 w-8/12  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-3">
            <flux:icon name="pencil" class="size-5" />
            <flux:heading size="lg" class="font-bold border-b-2 uppercase">
                Vidéos
            </flux:heading>
            <a href="{{route('admin.video.index')}}"
               class="ml-auto bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="list" class="w-4 h-4"></i>
                <span>Liste</span>
            </a>
        </div>
        <form wire:submit.prevent="save">
            @csrf
            <div class="space-y-6">
                <flux:radio.group wire:model="typevideo" label="Type Vidéo" variant="pills">
                    <flux:radio value="Camer" label="Camer" />
                    <flux:radio value="Sopie" label="Sopie" />
                </flux:radio.group>

                <flux:input
                    wire:model="titre"
                    type="text"
                    min="1"
                    label="Titre" size="xm" placeholder="Titre ..." />

                <div wire:ignore>
                    <flux:textarea

                        id="video"
                        label="Vidéo"
                        placeholder="Vidéo..."
                    />
                </div>
                <div>
                    @error('video') <span class="error">{{ $message }}</span> @enderror
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

            const textarea = document.querySelector('#video');

            if (!textarea) {
                return;
            }

            // Évite une double initialisation
            if (tinymce.get('video')) {
                return;
            }

            tinymce.init({
                selector: '#video',

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
                            'video',
                            editor.getContent()
                        );
                    });

                    // Synchronisation initiale
                    editor.on('init', function() {

                        const value = $wire.get('video');

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
