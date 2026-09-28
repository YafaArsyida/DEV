<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DokumenAdministrasi extends Controller
{
    public function index()
    {
        return view('keuangan.sistem.dokumen-administrasi');
    }
}
