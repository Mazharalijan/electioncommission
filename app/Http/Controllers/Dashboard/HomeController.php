<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('Dashboard.home');
    }

    public function vottinglist()
    {
        return view('Operators.entervotes');
    }
}
