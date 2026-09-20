<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Http\Resources\VideoResource;
use App\IRepository\IVideoRepository;
use App\Models\Video;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class VideoRepository extends Repository implements IVideoRepository{
    private int $perPage=10;
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
        //$videos= Video::orderBy('idvideo','desc')->paginate(10);
        return VideoResource::collection($videos);
    }
    function indexPaginated()
    {

        $ids = Video::orderByDesc('idvideo')->take(100)->pluck('idvideo');
        return Video::whereIn('idvideo', $ids)
            ->orderByDesc('idvideo')
            ->paginate($this->perPage);
        //return VideoResource::collection($videos);
    }

    /**
     * @param array $input
     * @return mixed
     */
    function create(array $input)
    {
        $input['titre']=Str::title($input['titre']);
        $input['video']=Helper::getYouTubeId($input['video']);
        $input['typevideo']=Str::ucfirst($input['typevideo']);
        //$video = parent::create($input);
        return new VideoResource(parent::create($input));
        //return VideoResource::collection();
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
        return  new VideoResource(parent::findById($id));
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
        //$input['video']= $input['video'] ?? $currentVideo->video;
        $input['video']= Helper::getYouTubeId($input['video']) ?? $currentVideo->video;
        return  new VideoResource(parent::update($input, $id));
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
        //Cache::forget('VideoSopie');
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
