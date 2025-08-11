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
            'images' => $this->getFeaturedImage(),

        ];
    }

    protected function getFeaturedImage()
    {
        if (!$this->relationLoaded('media')) {
            return null;
        }
        /*$media =$article->getMedia('article')->where('name',$article->slug)->first();*/
        $media = $this->getFirstMedia('article');
        //$media = $this->getMedia('article')->where('name',$this->slug)->first();
        //dd($media);
        return $media ? [
            'url' => $media->getUrl(),
            'mime_type' => $media->mime_type,
            'extension' => $media->extension,
            'width'=>$media->getCustomProperty('width'),
            'height'=>$media->getCustomProperty('height'),
            'meta' => $media->custom_properties
        ] : null;
    }
}
