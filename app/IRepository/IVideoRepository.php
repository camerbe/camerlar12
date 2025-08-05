<?php

namespace App\IRepository;

interface IVideoRepository
{
    function getVideoWeek();
    function getCamerVideo();
    function getRandomVideo();
    function findAll($camer='Camer');
}
