<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartCanteenDashboard extends Controller
{
    public function index()
    {
        return view('SMARTCANTEEN.dashboard.v_index');
    }
}
