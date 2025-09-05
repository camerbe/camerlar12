<?php

namespace App\Traits;

use App\Models\Pub;
use App\Models\Pubdimension;
use App\Models\Pubtype;

trait DimensionAndType
{
    function getDimension(){
        return Pubdimension::orderBy('dimension','asc')->get();
    }
    function getTypePub(){
        return Pubtype::orderBy('pubtype','asc')->get();
    }
}
