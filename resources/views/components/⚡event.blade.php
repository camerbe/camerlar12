<?php

use Livewire\Component;

new class extends Component
{
    public $event;

    //
    public function mount($event){
        $this->event=$event;
        //dd($this->event);

    }
};
?>

<div>
    <div class="flex flex-col items-center justify-center">
        <span class="text-[10px] uppercase text-gray-400 font-semibold tracking-wider mb-1 capitalize">évènement</span>
        <div class="h-full w-full bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-xs text-gray-500 rounded">

            <img
                src="{{$this->event['image_url']}}"
                alt="{{$this->event['eventdate']}} | Camer.be"
                class="w-full h-full rounded-md object-cover group-hover:scale-105 transition duration-300"
            >

        </div>
    </div>
</div>
