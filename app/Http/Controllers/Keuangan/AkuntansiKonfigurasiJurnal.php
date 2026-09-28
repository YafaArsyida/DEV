<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkuntansiKonfigurasiJurnal extends Controller
{
    public function index()
    {
        return view('keuangan.akuntansi.konfigurasi-jurnal');
    }
}
