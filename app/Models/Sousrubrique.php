<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Sousrubrique extends Model
{
    //
    protected $primaryKey  = 'idsousrubrique';
    public $timestamps = false;
    protected $table='sousrubriques';
    protected $fillable = [
        'sousrubrique', 'fkrubrique',
    ];
    public function rubrique():BelongsTo
    {
        return $this->belongsTo(Rubrique::class,'fkrubrique');
    }
    public function articlesousrubriques():HasMany{
        return $this->hasMany(Article::class,'fksousrubrique');
    }
    protected static function boot(){
        parent::boot();
        $cacheKey = "sousrubrique-cache";
        Sousrubrique::created(function ($model) use($cacheKey){
            Cache::forget($cacheKey);
        });
        Sousrubrique::updated(function ($model) use($cacheKey){
            Cache::forget($cacheKey);
        });
        Sousrubrique::deleted(function ($model) use($cacheKey){
            Cache::forget($cacheKey);
        });
    }
}
