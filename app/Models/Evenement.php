<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Evenement extends Model implements HasMedia
{
    //
    use InteractsWithMedia;
    protected $primaryKey  = 'idevent';
    public $timestamps = false;
    protected $table='events';
    protected $fillable = [
        'eventdate', 'affiche','imagewidth','imageheight',
    ];
    protected static function boot()
    {
        parent::boot();
        Evenement::created(function ($model) {
            Cache::forget('evenement');

        });
        Evenement::updated(function ($model) {
            Cache::forget('evenement');

        });
        Evenement::deleted(function ($model) {
            Cache::forget('evenement');

        });


    }
    public function registerMediaCollections():void{
        $this->addMediaCollection('evenement')
            ->registerMediaConversions(function(Media $media){
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);
            } );
        $this
            ->addMediaCollection('evenement')
            ->withResponsiveImages();
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('evenement')->map(function ($media) {
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
