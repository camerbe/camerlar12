<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PubResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->idpub,
            'endpubdate'=>$this->endpubdate,
            'fkdimension'=>$this->fkdimension,
            'fktype'=>$this->fktype,
            'pub'=>$this->pub,
            'imagewidth'=>$this->imagewidth,
            'imageheight'=>$this->imageheight,
            'editor'=>$this->href,
            'dimensions'=>$this->dimensions,
            'typepubs'=>$this->typepubs,


        ];
    }
}
