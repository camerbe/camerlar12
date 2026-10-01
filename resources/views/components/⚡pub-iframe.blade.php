<?php

use Livewire\Component;

new class extends Component
{
    //
    public $iframe;
    protected $pub;
    protected $isIframe=false;
    //
    public function mount($iframe){
        //dd($iframe);
        $this->iframe=$iframe[0];
        $this->pub=\App\Helpers\Helper::extractFromParagraph($this->iframe['pub']);
        if($this->pub) $this->isIframe=true;

    }
};
?>

<div>
    <div class="flex flex-col items-center justify-center">
        @if($this->isIframe)
        <span class="text-[10px] uppercase text-gray-400 font-semibold tracking-wider mb-1 capitalize">{{$this->iframe['editor']}}</span>
        <div class="h-full w-full bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-xs text-gray-500 rounded">

            <iframe
                src="{{$this->pub}}"
                class="w-full h-full rounded-xl"
                title="{{$this->iframe['editor']}}"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen
            ></iframe>

        </div>
        @endif
    </div>
</div>
