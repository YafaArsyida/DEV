<?php

namespace App\Http\Controllers\PPDB\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('ppdb.auth.register');
    }
}
