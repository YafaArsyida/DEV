<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkuntansiLaporanBukuBesar extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-buku-besar.v_index');
    }
}
