<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pays extends Model
{
    protected $primaryKey  = 'idpays';
    protected $keyType = 'string';
    protected $table='pays';

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'idpays', 'pays', 'country',
    ];
    public function articles():HasMany
    {
       return $this->hasMany(Article::class,'fkpays');
    }
}
