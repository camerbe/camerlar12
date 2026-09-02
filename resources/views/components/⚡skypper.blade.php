<?php

use Livewire\Component;

new class extends Component
{
    public $skypper;

    //
    public function mount($skypper){
        $this->skypper=$skypper;

    }
};
?>

<div>
    <div class="flex flex-col items-center justify-center">
        <span class="text-[10px] uppercase text-gray-400 font-semibold tracking-wider mb-1">Publicité</span>
        <div class="h-[250px] w-[300px] bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-xs text-gray-500 rounded">
            <a href="{{$this->skypper['href']}}" target="_blank" rel="noopener sponsored" class="w-full h-full flex items-center justify-center">
                <img
                    src="{{$this->skypper['image_url']}}"
                    alt="{{$this->skypper['editor']}}"
                >
            </a>
        </div>
    </div>
</div>
