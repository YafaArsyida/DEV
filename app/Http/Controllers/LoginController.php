<?php

namespace App\Http\Controllers;

use App\Models\ModelPengguna;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('login.v_index');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->with('loginError', 'Login gagal !');
        }

        $user = Auth::user();

        $sessionId = session()->getId();

        if ($user->current_session && $user->current_session !== $sessionId) {
            $user->current_session = null;
        }

        $user->current_session = $sessionId;
        $user->save();

        $request->session()->regenerate();

        return redirect()->intended(route('portal'));
    }

    public function logOut(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->current_session = null;
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
        // return redirect('/login');
    }
}
