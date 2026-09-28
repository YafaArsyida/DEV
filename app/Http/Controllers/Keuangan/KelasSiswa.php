<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelasSiswa extends Controller
{
    public function index()
    {
        return view('keuangan.administrasi.kelas-siswa');
    }
}
