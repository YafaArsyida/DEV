<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HierarkiKepegawaian extends Controller
{
    public function index()
    {
        return view('ADMINISTRASI.hierarki-kepegawaian.v_index');
    }
}
