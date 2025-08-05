<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Video extends Model
{
    //
    protected $primaryKey  = 'idvideo';
    public $timestamps = false;
    protected $table='sopieprod';
    protected $fillable = [
        'titre', 'video', 'typevideo',
    ];
    protected static function boot()
    {
        parent::boot();
        Video::created(function ($model) {
            if($model->typevideo=="Camer")
            {
                Cache::forget('VideoCamer');
                //Cache::forget('videorandomcamer');
            }
            elseif($model->typevideo=="Sopie") {
                Cache::forget('VideoSopie');
                //Cache::forget('videorandomsopie');
            }



        });
        Video::deleted(function ($model) {
            if($model->typevideo=="Camer")
            {
                Cache::forget('VideoCamer');
                //Cache::forget('videorandomcamer');
            }
            elseif($model->typevideo=="Sopie") {
                Cache::forget('VideoSopie');
                //Cache::forget('videorandomsopie');
            }

        });
    }
}
