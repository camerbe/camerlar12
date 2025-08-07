<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
