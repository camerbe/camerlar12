<?php

namespace App\IRepository;

interface IPaysRepository
{
    function allPays();

    function articleNonCameroon();
    function articleCameroon();

}
