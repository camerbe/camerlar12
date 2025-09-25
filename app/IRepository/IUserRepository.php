<?php

namespace App\IRepository;

interface IUserRepository
{
    function changePassword(array $input, $id);
    function firstLogin(array $input);

    function getUserByEmail(string $email);
}
