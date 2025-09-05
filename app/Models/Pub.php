<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Pub extends Model implements HasMedia
{
    //
    use InteractsWithMedia;
    protected $primaryKey  = 'idpub';
    public $timestamps = false;
    protected $table='pubs';
    protected $fillable = [
        'endpubdate', 'fkdimension', 'fktype','pub','editor',
        'href','imagewidth','imageheight',
    ];

    protected static function boot(){
        parent::boot();
        Pub::created(function ($model){
            $cache=($model->fkdimension===728)? 'Pub-728':'Pub-300';
            Cache::forget($cache);
            Cache::forget('pub-list-cache');
        });
        Pub::updated((function ($model){
            $cache=($model->fkdimension===728)? 'Pub-728':'Pub-300';
            Cache::forget($cache);
            Cache::forget('pub-list-cache');
        }));
        Pub::deleted((function ($model){
            $cache=($model->fkdimension===728)? 'Pub-728':'Pub-300';
            Cache::forget($cache);
            Cache::forget('pub-list-cache');
        }));
    }
    public function dimensions():BelongsTo{
        return $this->belongsTo(Pubdimension::class,'fkdimension');
    }
    public function typepubs():BelongsTo {
        return $this->belongsTo(Pubtype::class,'fktype');
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
