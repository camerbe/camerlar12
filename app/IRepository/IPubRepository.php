<?php

namespace App\IRepository;

interface IPubRepository
{
    function getCachedPub($dimension);

    function allPubDimension();
    function allPubType();
}
