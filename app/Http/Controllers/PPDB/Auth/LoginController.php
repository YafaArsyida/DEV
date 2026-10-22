<?php

namespace App\Http\Controllers\PPDB\Auth;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index(string $jenjang)
    {
        $jenjang = Jenjang::where('slug', $jenjang)
            ->where('status', 'aktif')
            ->firstOrFail();

        return view('ppdb.auth.login', compact('jenjang'));
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
