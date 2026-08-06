<?php

use Livewire\Component;

new class extends Component
{
    public $debat;
    //
};
?>

<div>
    <div class="bg-gray-900 text-white p-6 rounded-xl">
        <h3 class="text-lg font-bold font-heading mb-2 uppercase">le récent débat</h3>
        <img
            src="{{$debat['image_url'] ??'https://picsum.photos/600/400?random=4' }}"
            alt="$debat['titre']"
            class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
        />
        <p class="text-xs text-gray-300 mt-4 md:text-md">
            {{$debat['titre']}}
        </p>
    </div>
</div>
