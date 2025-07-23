<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManajemenKepegawaian extends Controller
{
    public function index()
    {
        return view('ADMINISTRASI.manajemen-kepegawaian.v_index');
    }
}
