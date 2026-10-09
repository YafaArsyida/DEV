<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class LandingEkstrakurikuler extends Controller
{
    public function index()
    {
        return view('LANDING-PAGE.ekstrakurikuler.v_index');
    }
}
