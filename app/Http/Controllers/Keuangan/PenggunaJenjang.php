<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenggunaJenjang extends Controller
{
    public function index()
    {
        return view('keuangan.sistem.pengguna-jenjang');
    }
}
