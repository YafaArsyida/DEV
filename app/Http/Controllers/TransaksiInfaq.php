<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiInfaq extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.infaq.v_index');
    }
}
