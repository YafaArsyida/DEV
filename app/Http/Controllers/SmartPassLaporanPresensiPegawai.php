<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartPassLaporanPresensiPegawai extends Controller
{
    public function index()
    {
        return view('SMARTPASS.LAPORAN.presensi-pegawai.v_index');
    }
}
