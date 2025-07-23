<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JenjangTahunAjar extends Controller
{
    public function index()
    {
        return view('SISTEM.jenjang_tahun_ajar.v_index');
    }
}
