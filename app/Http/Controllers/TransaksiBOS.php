<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiBOS extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.BOS.v_index');
    }
}
