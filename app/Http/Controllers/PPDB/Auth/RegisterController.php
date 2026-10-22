<?php

namespace App\Http\Controllers\PPDB\Auth;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;

class RegisterController extends Controller
{
    public function index(string $jenjang)
    {
        $jenjang = Jenjang::where('slug', $jenjang)
            ->where('status', 'aktif')
            ->firstOrFail();

        return view('ppdb.auth.register', compact('jenjang'));
    }

}
