<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\Jenjang;

class PPDBLandingController extends Controller
{
    /**
     * Halaman utama PPDB.
     */
    public function index()
    {
        return view('ppdb.landing.index');
    }

    public function sd()
    {
        $jenjang = Jenjang::where('slug', 'sd')
            ->where('status', 'aktif')
            ->firstOrFail();

        return view('ppdb.landing.sd', compact('jenjang'));
    }

    public function tk()
    {
        $jenjang = Jenjang::where('slug', 'tk')
            ->where('status', 'aktif')
            ->firstOrFail();

        return view('ppdb.landing.tk', compact('jenjang'));
    }

    public function kb()
    {
        $jenjang = Jenjang::where('slug', 'kb')
            ->where('status', 'aktif')
            ->firstOrFail();

        return view('ppdb.landing.kb', compact('jenjang'));
    }

    public function login()
    {
        return view('ppdb.auth.login');
    }
}
