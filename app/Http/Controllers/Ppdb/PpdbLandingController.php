<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;

class PpdbLandingController extends Controller
{
    public function index()
    {
        return view('ppdb.landing.index');
    }

    public function login()
    {
        return view('ppdb.auth.login');
    }
}
