<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->idarticle,
            'info'=>$this->info,
            'titre'=>$this->titre,
            'fkpays'=>$this->fkpays,
            'fkrubrique'=>$this->fkrubrique,
            'dateparution'=>$this->dateparution,
            'hit'=>$this->hit,
            'fksousrubrique'=>$this->fksousrubrique,
            'auteur'=>$this->auteur,
            'source'=>$this->source,
            'keyword'=>$this->keyword,
            'image'=>$this->image,
            'slug'=>$this->slug,
            'chapeau'=>$this->chapeau,
            'countries'=>$this->countries,
            'rubrique'=>$this->rubrique,
            'sousrubrique'=>$this->sousrubrique,

        ];
    }
}
