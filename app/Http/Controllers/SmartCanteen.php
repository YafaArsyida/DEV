<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartCanteen extends Controller
{
    public function index()
    {
        return view('SMARTCANTEEN.marketplace.v_index');
    }
}
