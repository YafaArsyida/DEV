<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenempatanEkstrakurikulerSiswa extends Controller
{
    public function index()
    {
        return view('keuangan.administrasi.ekstrakurikuler-siswa');
    }
}
