<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        return view('akademik.tahun-ajaran');
    }
}
