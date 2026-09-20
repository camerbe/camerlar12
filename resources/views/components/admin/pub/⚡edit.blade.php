<?php

use Livewire\Component;
use App\Services\PubService;
use Livewire\Attributes\Validate;
use Flux\Flux;
use Carbon\Carbon;

new class extends Component
{
    //
    #[Validate('required', message: 'La publicité est requise.')]
    public $pub = '';

    #[Validate('required', message: 'La dimension est requise.')]
    public $dimensions;

    #[Validate('required', message: 'Le type de publicité est requis.')]
    public $pubtypes;

    #[Validate('required', message: 'Faites le choix de la dimension.')]
    public ?int $fkdimension =null;

    #[Validate('required', message: 'Faites le choix du type de publicité.')]
    public ?int $fktype=null;

    #[Validate('date', message: 'La date fin est requise.')]
    public ?string  $endpubdate=null;

    #[Validate('required', message: 'Le lien est requis.')]
    public string $href='';

    #[Validate('required', message: "L'éditeur est requis.")]
    public string $editor='';

    public $item;
    public $id;

    public function mount(PubService $pubService){
        $this->dimensions=$pubService->allPubDimension();
        $this->pubtypes=$pubService->allPubType();

        $this->item= $pubService->findById($this->id);

        $this->pub=$this->item->pub;
        $this->fkdimension=$this->item->fkdimension;
        $this->fktype=$this->item->fktype;
        $this->endpubdate=Carbon::parse($this->item->endpubdate)->format('Y-m-d')?? null;
        $this->href=$this->item->href;
        $this->editor=$this->item->editor;


    }
    public function save(PubService $pubService){
        $validated = $this->validate();
        $publicite=[
            'pub'=>$this->pub,
            'fkdimension'=>$this->fkdimension,
            'fktype'=>$this->fktype,
            'endpubdate'=>$this->endpubdate,
            'href'=>$this->href,
            'editor'=>$this->editor,
        ];

        $pubService->update($publicite,$this->id);

        Flux::toast(
            heading: 'Mise à jour',
            text: 'Publicité mise à jour avec succès !',
            variant: 'success',
        );
        return $this->redirectRoute('admin.pub.index',navigate: true );
    }
};
?>

<div>
    <flux:card class="space-y-6 w-full  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-3">
            <flux:icon name="pencil" class="size-5" />
            <flux:heading size="lg" class="font-bold border-b-2 uppercase">
                Publicités
            </flux:heading>
            <a href="{{route('admin.pub.index')}}"
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
                            wire:model="editor"
                            type="text"
                            min="1"
                            label="Editeur" size="xm" placeholder="Editeur ..." />
                    </div>
                    <div class="flex-1">
                        <flux:input
                            wire:model="href"
                            type="text"
                            min="1"
                            label="Lien" size="xm" placeholder="Lien ..." />
                    </div>
                    <div class="flex-1">
                        <flux:input
                            wire:model="endpubdate"
                            type="date"
                            min="1"
                            label="Date de Fin" size="xm" placeholder="Date de Fin ..." />
                    </div>


                </div>
                <div class="flex flex-row gap-4">
                    <div class="flex-1">
                        <flux:select wire:model="fkdimension" label="Dimension" placeholder="Choix de la Dimension...">
                            @foreach($this->dimensions as $dimension)
                                <flux:select.option value="{{$dimension->idpubdimension}}">{{$dimension->dimension}}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div class="flex-1">
                        <flux:select wire:model="fktype" label="Type de publicité" placeholder="Choix du type de pub..">
                            @foreach($this->pubtypes as $pubtype)
                                <flux:select.option value="{{$pubtype->idpubtype}}">{{$pubtype->pubtype}}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>

                <div wire:ignore>
                    <flux:textarea

                        id="pub"
                        label="Publicité"
                        placeholder="Publicité..."
                    />
                </div>
                <div>
                    @error('pub') <span class="error text-red-500">{{ $message }}</span> @enderror
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

            const textarea = document.querySelector('#pub');

            if (!textarea) {
                return;
            }

            // Évite une double initialisation
            if (tinymce.get('pub')) {
                return;
            }

            tinymce.init({
                selector: '#pub',

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
                            'pub',
                            editor.getContent()
                        );
                    });

                    // Synchronisation initiale
                    editor.on('init', function() {

                        const value = $wire.get('pub');

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
