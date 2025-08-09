<?php

namespace App\IRepository;

interface IUserRepository
{
    function changePassword(array $input, $id);
}
