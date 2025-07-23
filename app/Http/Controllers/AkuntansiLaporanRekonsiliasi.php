<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkuntansiLaporanRekonsiliasi extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-rekonsiliasi.v_index');
    }
}
