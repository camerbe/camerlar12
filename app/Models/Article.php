<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Article extends Model implements HasMedia
{
    //
    use HasFactory,InteractsWithMedia;
    protected $primaryKey  = 'idarticle';
    public $timestamps = false;
    protected $table='articles';
    protected $fillable = [
        'info', 'titre', 'fkpays','fkrubrique','chapeau',
        'fkuser','dateparution','dateref','fksousrubrique','auteur','source',
        'keyword','image','imagewidth','imageheight','slug',
    ];
    protected static function boot(){
        //$users=User::all();
        parent::boot();
        Article::created(function ($model) {
            //Cache::forget('Article-By-User');
            Cache::forget('Article-CMR-list');
            Cache::forget('Article-Other-list');
            Cache::forget('news_for_rss');
            /*foreach ($users as $user){
                $cacheKey = "Article-By-User-".$user;
                Cache::forget($cacheKey);
            }*/
        });
        Article::updated(function ($model)  {
            //Cache::forget('Article-By-User');
            Cache::forget('Article-CMR-list');
            Cache::forget('Article-Other-list');
            Cache::forget('news_for_rss');
            /*foreach ($users as $user){
                $cacheKey = "Article-By-User-".$user;
                Cache::forget($cacheKey);
            }*/
        });
        Article::deleted(function ($model) {
            //Cache::forget('Article-By-User');
            Cache::forget('Article-CMR-list');
            Cache::forget('Article-Other-list');
            Cache::forget('news_for_rss');
            /*foreach ($users as $user){
                $cacheKey = "Article-By-User-".$user;
                Cache::forget($cacheKey);
            }*/
        });
    }
    public function countries():BelongsTo{
        return $this->belongsTo(Pays::class,'fkpays');
    }
    public function rubrique():BelongsTo{
        return $this->belongsTo(Rubrique::class,'fkrubrique');
    }
    public function sousrubrique():BelongsTo{
        return $this->belongsTo(Sousrubrique::class,'fksousrubrique');
    }

    protected $with = ['media'];
    public function registerMediaCollections():void{
        $this->addMediaCollection('article')
            ->registerMediaConversions(function(Media $media){
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);
            } );
        $this
            ->addMediaCollection('article')
            ->withResponsiveImages();
    }
    public function getImagesAttribute()
    {
        return $this->getMedia('article')->map(function ($media) {
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
