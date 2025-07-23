<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EkstrakurikulerSiswa extends Controller
{
    public function index()
    {
        return view('ADMINISTRASI.ekstrakurikuler-siswa.v_index');
    }
}
