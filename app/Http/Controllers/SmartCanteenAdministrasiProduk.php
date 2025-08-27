<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartCanteenAdministrasiProduk extends Controller
{
    public function index()
    {
        return view('SMARTCANTEEN.administrasi-produk.v_index');
    }
}
