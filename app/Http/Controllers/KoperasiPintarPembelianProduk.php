<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KoperasiPintarPembelianProduk extends Controller
{
    public function index()
    {
        return view('KOPERASIPINTAR.pembelian-produk.v_index');
    }
}
