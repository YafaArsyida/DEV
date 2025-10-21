<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanTabunganPegawai extends Controller
{
    public function index()
    {
        return view('LAPORAN.tabungan-pegawai.v_index');
    }
}
