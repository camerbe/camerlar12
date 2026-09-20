<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\V1\AuthController;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //


    public function show(){
        return view('auth.login');

    }



}
