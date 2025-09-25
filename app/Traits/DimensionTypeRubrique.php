<?php

namespace App\Traits;

use App\Models\Pubdimension;
use App\Models\Pubtype;
use App\Models\Rubrique;

trait DimensionTypeRubrique
{
    function getDimension(){
        return Pubdimension::orderBy('dimension')->get();
    }
    function getPubType()
    {
        return Pubtype:: orderBy('pubtype', 'asc')->get();
    }
    function getRubrique(){
        return Rubrique::orderBy('rubrique','asc')->get();
    }
}
