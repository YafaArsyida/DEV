<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkuntansiLaporanNeraca extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-neraca.v_index');
    }
}
