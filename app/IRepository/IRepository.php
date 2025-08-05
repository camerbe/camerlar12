<?php

namespace App\IRepository;

interface IRepository
{
    function create(Array $input);
    function delete($id);
    function findById($id);
    function update(Array $input, $id);
    function index();

}
