<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanRekapitulasiKeuangan extends Controller
{
    public function index()
    {
        return view('LAPORAN.rekapitulasi-keuangan.v_index');
    }
}
