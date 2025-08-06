<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rubrique extends Model
{
    protected $primaryKey  = 'idrubrique';
    public $timestamps = false;
    protected $table='rubriques';
    protected $fillable = [
        'rubrique',
    ];
    public function sousrubriques():HasMany
    {
        return $this->hasMany(Sousrubrique::class,'fkrubrique');
    }
}
