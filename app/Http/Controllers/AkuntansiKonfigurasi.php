<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkuntansiKonfigurasi extends Controller
{
    public function index()
    {
        return view('AKUNTANSI.konfigurasi.v_index');
    }
}
