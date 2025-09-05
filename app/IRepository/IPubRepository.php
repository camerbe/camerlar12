<?php

namespace App\IRepository;

interface IPubRepository
{
    function getCachedPub($dimension);

    function getPubDimension();
    function getPubType();
}
