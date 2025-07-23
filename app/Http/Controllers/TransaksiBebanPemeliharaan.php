<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiBebanPemeliharaan extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.beban-pemeliharaan.v_index');
    }
}
