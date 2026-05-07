<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartCanteenAdministrasiKantin extends Controller
{
    public function index()
    {
        return view('SMARTCANTEEN.administrasi-kantin.v_index');
    }
}
