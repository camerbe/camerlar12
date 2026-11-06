<?php

use Livewire\Component;


new class extends Component
{
    //
    /*public $iframe;
    protected $pub;
    protected $isIframe=false;
    */
    //
    public $iframe;
    public ?string $pub = null;
    public bool $isIframe = false;
    public string $type = 'infomaniak';

    /*public function mount($iframe){

        $this->iframe=$iframe[0]?? null;
        if (is_null($this->iframe)) return;
        $this->pub=\App\Helpers\Helper::extractFromParagraph($this->iframe['pub']);
        if($this->pub) $this->isIframe=true;

    }*/
    public function mount($iframe, string $type = 'infomaniak')
    {
        $this->type = $type;

        if (is_object($iframe) && method_exists($iframe, 'toArray')) {
            $iframe = $iframe->toArray();
        }

        // Liste d'enregistrements → on prend le premier
        if (is_array($iframe) && array_is_list($iframe)) {
            $iframe = $iframe[0] ?? null;
        }

        $this->iframe = $iframe;
        if (is_null($this->iframe)) return;

        $this->pub = \App\Helpers\Helper::extractFromParagraph($this->iframe['pub']);
        //$this->isIframe = (bool) $this->pub;
        $this->isIframe = true;
    }
    public function getLabelProperty(): string
    {
        return $this->type === 'iframe'
            ? 'publicité'
            : ($this->iframe['editor'] ?? '');
    }
};
?>

<div>
    <div class="flex flex-col items-center justify-center">
        @if($isIframe)
            <span class="text-[10px] uppercase text-gray-400 font-semibold tracking-wider mb-1 capitalize">
                {{ $this->label }}
            </span>

            <div class="h-full w-full bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-xs text-gray-500 rounded">

                @if($type === 'infomaniak')
                    <iframe
                        src="{{ $pub }}"
                        class="w-full h-full rounded-xl"
                        title="{{ $this->label }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen
                    ></iframe>
                @else
                    <iframe
                        src="{{ $pub }}"
                        class="w-full h-full rounded-xl"
                        title="{{ $this->label }}"
                        allowfullscreen
                    ></iframe>
                @endif

            </div>
        @endif
    </div>
</div>
