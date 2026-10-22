<?php

namespace App\Http\Controllers\PPDB\Auth;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;
use App\Models\PPDBPendaftaran;
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

        $jenjang = null;

        if ($user) {
            $pendaftaran = PPDBPendaftaran::query()
                ->with('ppdb_periode.ms_jenjang')
                ->where('ms_pengguna_id', $user->ms_pengguna_id)
                ->latest('created_at')
                ->first();

            $jenjang = $pendaftaran?->ppdb_periode?->ms_jenjang?->slug;

            $user->current_session = null;
            $user->save();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($jenjang) {
            return redirect("/ppdb/{$jenjang}");
        }

        return redirect('/ppdb');
    }
}
