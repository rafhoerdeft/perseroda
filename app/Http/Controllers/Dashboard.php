<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboard extends UserBaseController
{
    public function index()
    {
        return view('pages/index2');
    }
}
