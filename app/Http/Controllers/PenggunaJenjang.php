<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenggunaJenjang extends Controller
{
    public function index()
    {
        return view('SISTEM.pengguna-akses-jenjang.v_index');
    }
}
