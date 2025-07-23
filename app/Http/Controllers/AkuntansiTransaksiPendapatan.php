<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkuntansiTransaksiPendapatan extends Controller
{
    public function index()
    {
        return view('AKUNTANSI.transaksi-pendapatan.v_index');
    }
}
