<?php

namespace App\Http\Controllers\PPDB\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index()
    {
        return view('ppdb.auth.login');
    }
    
    public function logOut(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->current_session = null;
            // $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('ppdb.landing');
    }
}
