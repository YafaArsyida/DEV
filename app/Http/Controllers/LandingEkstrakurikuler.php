<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingEkstrakurikuler extends Controller
{
    public function index()
    {
        return view('LANDING-PAGE.ekstrakurikuler.v_index');
    }
}
