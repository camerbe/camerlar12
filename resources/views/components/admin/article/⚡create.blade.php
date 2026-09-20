<?php

use Livewire\Component;
use App\Services\ArticleService;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Rule;

new class extends Component
{

    #[Validate(
        'required|string|regex:/^#[\p{L}\p{M}\p{N}_\-]{2,}(?:,\s*#[\p{L}\p{M}\p{N}_\-]{2,}){0,4}$/u',
        message: [
            'hashtags.required' => 'Le champ hashtags est obligatoire.',
            'hashtags.regex' => 'Le format est invalide : entrez 1 à 5 hashtags (3 caractères minimum chacun, ex: #dev, #Foumban), séparés par des virgules.',
        ]
    )]
    public string $hashtags = '';

    #[Validate('required', message: "L'auteur  est requis.")]
    public string $auteur = '';

    #[Validate('required', message: "La source est requise.")]
    public string $source = '';

    #[Validate('required', message: "L'article est requis.")]
    public string $info = '';

    #[Validate('required', message: "L'image est requise.")]
    public string $image = '';

    #[Validate('required', message: "Le titre est requis.")]
    #[Validate('max:100', message: "Le titre ne peut pas dépasser 100 caractères")]
    public string $titre = '';

    #[Validate('required', message: "La date est requise.")]
    public string $dateparution = '';

    #[Validate('required', message: "Le choix du pays  est requis.")]
    public string $fkpays = '';

    #[Validate('required', message: "Le choix de la rubrique est requis.")]
    public ?int $fkrubrique=null;

    #[Validate('integer', message: "Le choix de la sous rubrique  est requis.")]
    public ?int $fksousrubrique   = null;

    public $sousRubriques;
    public $bleds;



    public function mount(ArticleService $articleService){
        $this->sousRubriques=$articleService->allRubrique();
        $this->bleds=$articleService->allCountries();
    }

    #[Rule(
        ['required', 'string', 'regex:/^[\\p{L}\\p{N}\\-_\\s]{3,}(?:,\\s*[\\p{L}\\p{N}\\-_\\s]{3,}){0,4}$/u'],
        message: [
            'required' => 'Le champ mots-clés est obligatoire.',
            'regex' => 'Le format est invalide : entrez 1 à 5 mots-clés ou expressions (3 caractères minimum chacun), séparés par des virgules.'
        ]
    )]
    public string $keyword = '';
    /*
    public function messages()
    {
        return [
            'hashtags.regex' => 'Le hashtag doit commencer par # et contenir entre 3 et 5 caractères au total (ex: #dev).',
        ];
    }*/
    public function updated($property, $value)
    {
        if ($property === 'fksousrubrique') {
            if (!empty($value)) {
                $selected = collect($this->sousRubriques)->firstWhere('idsousrubrique', $value);
                $this->fkrubrique = $selected ? $selected->fkrubrique : null;
            } else {
                $this->fkrubrique = null;
            }
        }

    }
    public function save(ArticleService $articleService){
        //dd($this->all());
        $validated = $this->validate();
        try{

            $result=$articleService->create([
                'info' =>  $validated['info'],
                'titre' =>  $validated['titre'],
                'fkpays' =>  $validated['fkpays'],
                'fkuser' =>  auth()->id(),
                'fkrubrique' =>  $this->fkrubrique,
                'fksousrubrique' =>  $this->fksousrubrique,
                'dateparution' =>  $validated['dateparution'],
                'auteur' =>  $validated['auteur'],
                'source' =>  $validated['source'],
                'hashtags' =>  $validated['hashtags'],
                'keyword' =>  $validated['keyword'],
                'image' =>  $validated['image'],

            ]);

            Flux::toast(
                heading: 'Création',
                text: 'Article crée avec succès !',
                variant: 'success',
            );
            return $this->redirectRoute('admin.article.index', ['userid' => auth()->id()],navigate: true);
        }
        catch (\Throwable $e){
            dd($e);
            Flux::toast(
                heading: 'Erreur',
                text: "Erreur survenue lors de la création d'un article !",
                variant: 'danger',
            );
        }

    }



};
?>

<div>
    <flux:card class="space-y-6 w-full  mx-auto">
        <div class="flex items-center gap-3 border-b border-gray-300 pb-5 mb-3">
            <flux:icon name="plus" class="size-5" />
            <flux:heading size="lg" class="font-bold border-b-2 uppercase">
                Articles
            </flux:heading>
            <a href="{{route('admin.article.index',['userid' => auth()->id()])}}"
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
                            wire:model="auteur"
                            type="text"
                            min="1"
                            label="Auteur" size="xm" placeholder="Auteur ..." />
                    </div>
                    <div class="flex-1">
                        <flux:input
                            wire:model="source"
                            type="text"
                            min="1"
                            label="Source" size="xm" placeholder="Source ..." />
                    </div>
                    <div class="flex-1">
                        <flux:input
                            wire:model="dateparution"
                            type="datetime-local"
                            min="1"
                            label="Date Parution" size="xm" placeholder="Date  ..." />
                    </div>


                </div>
                <div class="flex flex-row gap-4">
                    <div class="flex-1">
                        <flux:select wire:model.live="fksousrubrique" label="Rubrique" placeholder="Choix de la Rubrique...">
                            @foreach($this->sousRubriques as $sousrubrique)
                                <flux:select.option value="{{$sousrubrique->idsousrubrique}}">{{$sousrubrique->sousrubrique}}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div class="flex-1">
                        <flux:select wire:model="fkpays" label="Pays" placeholder="Choix du Pays...">
                            @foreach($this->bleds as $bleds)
                                <flux:select.option value="{{$bleds->idpays}}">{{$bleds->pays}}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>

                <flux:input
                    wire:model="titre"
                    type="text"
                    min="1"
                    label="Titre" size="xm" placeholder="Titre ..." />
                <div class="flex flex-row gap-4">
                    <div class="flex-1">
                        <flux:input
                            wire:model="keyword"
                            type="text"
                            min="1"
                            label="Mots Clés" size="xm" placeholder="Mots clés ..." />
                    </div>
                    <div class="flex-1">
                        <flux:input
                            wire:model="hashtags"
                            type="text"
                            min="1"
                            label="Hashtags" size="xm" placeholder="Hashtags ..." />
                    </div>
                </div>

                <div wire:ignore>
                    <flux:textarea
                        class="tinymce-editor"
                        wire:model="info"
                        id="info"
                        label="Article"
                        placeholder="Article..."
                    />
                </div>

                <div>
                    @error('info') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div wire:ignore>
                    <flux:textarea
                        class="tinymce-editor"
                        id="image"
                        wire:model="image"
                        label="Photo"
                        placeholder="Image..."
                    />
                </div>

                <div>
                    @error('image') <span class="error text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-center">
                    <flux:button type="submit" class="mx-auto" icon="plus" variant="filled" color="blue">
                        Enregistrer
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
            // Sélectionne tous les textarea ayant la classe 'tinymce-editor'
            const textareas = document.querySelectorAll('textarea.tinymce-editor');

            if (!textareas.length) {
                return;
            }

            textareas.forEach((textarea) => {
                const editorId = textarea.id;

                if (!editorId) {
                    console.warn('Chaque textarea TinyMCE doit avoir un ID unique.');
                    return;
                }

                // Évite une double initialisation pour ce textarea spécifique
                if (tinymce.get(editorId)) {
                    return;
                }

                // Récupère la propriété Livewire via wire:model (ou utilise l'ID par défaut)
                const wireModel = textarea.getAttribute('wire:model') || editorId;
                let isMenu=true;
                // Définition conditionnelle des plugins et de la toolbar selon l'ID
                let pluginsList = ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'];

                let toolbarList = 'undo redo | blocks | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help';
                if (editorId === 'image') {
                    pluginsList = ['image', 'media'];
                    toolbarList = 'image media';
                    isMenu=false;
                }

                tinymce.init({
                    target: textarea,
                    relative_urls: false,
                    height: 600,
                    menubar: isMenu,
                    plugins: pluginsList,
                    toolbar: toolbarList,

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
                        // Synchronisation dynamique TinyMCE -> Livewire pour CE champ précis
                        editor.on('change input undo redo keyup', function() {
                            $wire.set(wireModel, editor.getContent());
                        });

                        // Synchronisation initiale depuis Livewire
                        editor.on('init', function() {
                            const value = $wire.get(wireModel);
                            if (value) {
                                editor.setContent(value);
                            }
                        });
                    }
                });
            });
        }

        // Initialisation au chargement
        initTinyMCE();

        // Utile avec Livewire + wire:navigate
        document.addEventListener('livewire:navigated', function() {
            initTinyMCE();
        });
    </script>
    @endscript
</div>
