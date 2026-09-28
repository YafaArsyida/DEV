<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KeuanganDashboard extends Controller
{
    public function index()
    {
        return view('keuangan.dashboard');
    }
}
