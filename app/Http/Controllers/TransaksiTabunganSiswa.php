<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiTabunganSiswa extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.tabungan-siswa.v_index');
    }
}
