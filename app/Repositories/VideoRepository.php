<?php

namespace App\Repositories;

use App\Http\Resources\VideoResource;
use App\IRepository\IVideoRepository;
use App\Models\Video;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class VideoRepository extends Repository implements IVideoRepository{

    public function __construct(Video $video)
    {
        parent::__construct($video);
    }

    /**
     * @return mixed
     */
    function index()
    {
        $videos= Video::orderBy('idvideo','desc')->take(150)->get();
        return VideoResource::collection($videos);
    }

    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input)
    {
        $input['titre']=Str::title($input['titre']);
        $input['typevideo']=Str::ucfirst($input['typevideo']);
        return VideoResource::collection(parent::create($input)); ;
    }

    /**
     * @param $id
     * @return mixed
     */
    function delete($id)
    {
        return parent::delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    function findById($id)
    {
        return VideoResource::collection(parent::findById($id)); ;
    }

    /**
     * @param array $input
     * @param $id
     * @return mixed
     */
    function update(array $input, $id)
    {
        //dd($id);
        //dd($input);
        $currentVideo=parent::findById($id);
        $input['titre']=isset($input['titre'])? Str::title($input['titre']):$currentVideo->titre;
        $input['typevideo']=isset($input['typevideo'])? Str::ucfirst($input['typevideo']):$currentVideo->typevideo;
        $input['video']= $input['video'] ?? $currentVideo->video;
        return  VideoResource::collection(parent::update($input, $id));
    }

    /**
     * @return mixed
     */
    function getVideoWeek()
    {
        $cacheKey = 'VideoSopie';
        if (!Cache::has($cacheKey)) {
            $this->findAll('Sopie');
        }
        return  VideoResource::collection(collect(Cache::get($cacheKey))->take(5));
    }

    /**
     * @return mixed
     */
    function getCamerVideo()
    {
        $cacheKey = 'VideoCamer';
        if (!Cache::has($cacheKey)) {
            $this->findAll('Camer');
        }
        return  VideoResource::collection(collect(Cache::get($cacheKey))->take(5));
    }

    /**
     * @return mixed
     */
    function getRandomVideo()
    {
        $cacheKey = 'VideoCamer';
        if (!Cache::has($cacheKey)) {
            $this->findAll('Camer');
        }
        $videos = Cache::get($cacheKey);
        return  VideoResource::collection(collect($videos)->shuffle()->take(10)->values());
    }

    /**
     * @param $camer
     * @return mixed
     */
    function findAll($camer = 'Camer')
    {
        if (!in_array($camer, ['Camer', 'Sopie'])) {
            return null;
        }
        $cacheKey = $camer === 'Camer' ? 'VideoCamer' : 'VideoSopie';
        $videos = Cache::remember($cacheKey, now()->addDay(1), function () use ($camer) {
            return Video::Where('typevideo',$camer)->orderByDesc('idvideo')->take(100)->get();
        });

        return  VideoResource::collection($videos);
    }

    /**
     * @param $camer
     * @return mixed
     */
    function getOneVideo($camer = 'Camer')
    {
        if (!in_array($camer, ['Camer', 'Sopie'])) {
            return null;
        }
        $cacheKey = $camer === 'Camer' ? 'OneVideoCamer' : 'OneVideoSopie';
        $videos = Cache::remember($cacheKey, now()->addDay(1), function () use ($camer) {
            return Video::Where('typevideo',$camer)->orderByDesc('idvideo')->first();
        });
        return new VideoResource($videos);
    }

}
