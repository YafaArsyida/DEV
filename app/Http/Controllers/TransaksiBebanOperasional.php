<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiBebanOperasional extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.beban-operasional.v_index');
    }
}
