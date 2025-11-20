<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KoperasiPintarAdministrasiProduk extends Controller
{
    public function index()
    {
        return view('KOPERASIPINTAR.administrasi-produk.v_index');
    }
}
