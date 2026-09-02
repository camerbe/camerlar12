<?php

use Livewire\Component;

new class extends Component
{
    public $banner;

    //
    public function mount($banner){
        $this->banner=$banner;

    }
};
?>

<div>
    <a href="{{$this->banner['href']}}" target="_blank" rel="noopener sponsored" class="w-full h-full flex items-center justify-center">
        <img
            src="{{$this->banner['image_url']}}"
            alt="{{$this->banner['editor']}}"
        >
    </a>
</div>
