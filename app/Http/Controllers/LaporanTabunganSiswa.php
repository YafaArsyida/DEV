<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanTabunganSiswa extends Controller
{
    public function index()
    {
        return view('LAPORAN.tabungan-siswa.v_index');
    }
}
