<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Pub extends Model
{
    //
    protected $primaryKey  = 'idpub';
    public $timestamps = false;
    protected $table='pubs';
    protected $fillable = [
        'endpubdate', 'fkdimension', 'fktype','pub','editor',
        'href','imagewidth','imageheight',
    ];
    public function dimensions():BelongsTo{
        return $this->belongsTo(Pubdimension::class);
    }
    public function typepubs():BelongsTo {
        return $this->belongsTo(Pubtype::class);
    }
    public function registerMediaCollections():void{
        $this->addMediaCollection('pub')
            ->registerMediaConversions(function(Media $media){
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);
            } );
        $this
            ->addMediaCollection('pub')
            ->withResponsiveImages();
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('pub')->map(function ($media) {
            return [
                'original' => $media->getUrl(),
                //'thumb' => $media->getUrl('thumb'), // Conversion
                'properties' => $media->custom_properties,
                'width' => $media->getCustomProperty('width'),
                'height'=> $media->getCustomProperty('height'),
            ];
        });
    }
}
