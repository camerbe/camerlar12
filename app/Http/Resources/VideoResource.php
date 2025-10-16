<?php

namespace App\Http\Resources;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->idvideo,
            'titre'=>$this->titre,
            'video'=>$this->video,
            'typevideo'=>$this->typevideo,
            'cover_image'=>$this->getThumbnail(),
            'video_url'=>$this->getUrl(),

        ];
    }

    protected function getThumbnail(){
        $url=Helper::FindYoutube($this->video);
        return Helper::getYouTubeThumbnail($url);
    }
    protected  function  getUrl(){
        return Helper::FindYoutube($this->video);
    }
}
