<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DokumenSiswa extends Controller
{
    public function index()
    {
        return view('ADMINISTRASI.dokumen-siswa.v_index');
    }
}
