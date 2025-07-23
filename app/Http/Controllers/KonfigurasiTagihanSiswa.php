<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KonfigurasiTagihanSiswa extends Controller
{
    public function index()
    {
        return view('KEUANGAN.konfigurasi-tagihan-siswa.v_index');
    }
}
