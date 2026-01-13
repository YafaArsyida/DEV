<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartPassLaporanFingerSpotPegawai extends Controller
{
    public function index()
    {
        return view('SMARTPASS.FINGERSPOT.presensi-pegawai.v_index');
    }
}
