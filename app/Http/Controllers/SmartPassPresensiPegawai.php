<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartPassPresensiPegawai extends Controller
{
    public function index()
    {
        return view('SMARTPASS.presensi_pegawai.v_index');
    }
}
