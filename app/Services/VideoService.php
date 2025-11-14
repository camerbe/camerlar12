<?php

namespace App\Services;

use App\IRepository\IVideoRepository;

class VideoService
{

    public function __construct(protected IVideoRepository $videoRepository)
    {
    }

    public function index(){
        return $this->videoRepository->index();
    }
    public function getCamerVideo()
    {
        return $this->videoRepository->getCamerVideo();
    }
    public function getRandomVideo()
    {
        return $this->videoRepository->getRandomVideo();
    }
    public function getVideoWeek()
    {
        return $this->videoRepository->getVideoWeek();
    }
    public function findAll($camer='Camer')
    {
        return $this->videoRepository->findAll($camer);
    }
    public function getOneVideo($camer='Camer')
    {
        return $this->videoRepository->getOneVideo($camer);
    }
    public function create($data){
        return $this->videoRepository->create($data);
    }
    public function findById($id){
        return $this->videoRepository->findById($id);
    }
    public function delete($id){
        return $this->videoRepository->delete($id);
    }
    public function update($data,$id){
        return $this->videoRepository->update($data,$id);
    }

}
