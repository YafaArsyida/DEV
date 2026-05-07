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
        // dd($credentials);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Hapus sesi lama jika ada
            $sessionId = session()->getId();
            if ($user->current_session && $user->current_session !== $sessionId) {
                $user->current_session = null;
            }

            $user->current_session = $sessionId;
            $user->save();

            $request->session()->regenerate();

            // Redirect sesuai peran
            if ($user->peran === 'KANTIN') {
                return redirect()->route('smartCanteen.dashboard');
            }

            return redirect()->route('dashboard.index');
        }

        return back()->with('loginError', 'Login gagal !');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
