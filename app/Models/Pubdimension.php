<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pubdimension extends Model
{
    //
    protected $primaryKey  = 'idpubdimension';
    protected $table='pubdimensions';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'dimension',
    ];
    public function pubdim():HasMany{
        return $this->hasMany(Pub::class);
    }
}
