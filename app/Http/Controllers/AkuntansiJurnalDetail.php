<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkuntansiJurnalDetail extends Controller
{
    public function index()
    {
        return view('AKUNTANSI.jurnal-detail.v_index');
    }
}
