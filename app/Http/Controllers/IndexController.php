<?php

namespace App\Http\Controllers;

use App\Mail\RegMail;
use App\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function home(){
        return view('home');
    }
}
