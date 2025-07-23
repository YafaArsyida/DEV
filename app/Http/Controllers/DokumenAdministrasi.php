<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DokumenAdministrasi extends Controller
{
    public function index()
    {
        return view('SISTEM.dokumen-administrasi.v_index');
    }
}
