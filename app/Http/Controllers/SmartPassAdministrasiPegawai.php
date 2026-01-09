<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartPassAdministrasiPegawai extends Controller
{
    public function index()
    {
        return view('SMARTPASS.ADMINNISTRASI.data-pegawai.v_index');
    }
}
