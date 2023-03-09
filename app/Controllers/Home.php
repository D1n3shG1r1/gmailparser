<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        die("Access Denied!");
        return view('welcome_message');
    }
}
