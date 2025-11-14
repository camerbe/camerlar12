<?php

namespace App\Http\Resources;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvenementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->idevent,
            'eventdate'=>$this->eventdate,
            'affiche'=>$this->affiche,
            'imagewidth'=>$this->imagewidth,
            'imageheight'=>$this->imageheight,
            'image_url' => $this->getImageUrl(),

        ];
    }
    protected function getImageUrl(){
        return Helper::extractImgSrc($this->affiche);
    }
}
