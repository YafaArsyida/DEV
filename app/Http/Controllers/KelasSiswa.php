<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelasSiswa extends Controller
{
    public function index()
    {
        return view('ADMINISTRASI.kelas-siswa.v_index');
    }
}
