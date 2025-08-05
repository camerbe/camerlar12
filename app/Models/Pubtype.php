<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pubtype extends Model
{
    //
    protected $primaryKey  = 'idpubtype';
    protected $table='pubtypes';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'pubtype',
    ];
    public function publicites():HasMany{
        return $this->hasMany(Pub::class);
    }
}
