<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartPassDashboard extends Controller
{
    public function index()
    {
        return view('SMARTPASS.DASHBOARD.v_index');
    }
}
