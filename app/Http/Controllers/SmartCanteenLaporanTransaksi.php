<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartCanteenLaporanTransaksi extends Controller
{
    public function index()
    {
        return view('SMARTCANTEEN.laporan-transaksi.v_index');
    }
}
