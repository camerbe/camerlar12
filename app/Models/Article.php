<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
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
            static::clearArticleCache($model);
        });

        Article::deleted(function ($model) {
            static::clearArticleCache($model);
        });

        Article::updated(function ($model) {
            static::clearArticleCache($model);
        });


    }
    protected static function clearArticleCache(self $model): void{

        Cache::forget('Article-list');
        Cache::forget('news_for_rss');
        Cache::forget('articles_json');
        Cache::forget('articles_droit_json');
        Cache::forget('articles_debat_json');
        for($i=0;$i<10;$i++){
            $idx=$i+1;
            $cacheKey='cahe_amp_index_'.$idx;
            Cache::forget($cacheKey);
        }
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
    // --- Helpers ---
    public function incrementHits(): void
    {
        $this->increment('hit');
    }
    // --- Scopes (Filtres réutilisables) ---
    public function scopePublished(Builder $query):Builder
    {
        return $query->where('dateparution','<=',now());
    }
    public function scopeCameroon(Builder $query):Builder
    {
        return $query->where('fkpays','=','CM')
                     ->where('dateparution','<=',now());
    }
    public function scopeOther(Builder $query):Builder
    {
        return $query->where('fkpays','<>','CM')
                     ->where('dateparution','<=',now());
    }
    public function scopeSearch(Builder $query,string $search)
    {
        $search = trim($search);
        if (empty($search)) {
            return $query;
        }
        return $query
            ->whereFullText(
                ['titre'],   // chercher dans plusieurs colonnes
                $search,
                ['mode' => 'boolean']   // mode boolean pour + de contrôle
            )
            ->orderByRaw(
                'MATCH(titre, contenu) AGAINST(? IN BOOLEAN MODE) DESC',
                [$search]              // trier par pertinence
            );
    }

}
