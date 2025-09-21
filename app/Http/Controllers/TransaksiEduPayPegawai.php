<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiEduPayPegawai extends Controller
{
    public function index()
    {
        return view('TRANSAKSI.edupay-pegawai.v_index');
    }
}
