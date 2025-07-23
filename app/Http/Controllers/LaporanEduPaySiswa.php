<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanEduPaySiswa extends Controller
{
    public function index()
    {
        return view('LAPORAN.edupay-siswa.v_index');
    }
}
