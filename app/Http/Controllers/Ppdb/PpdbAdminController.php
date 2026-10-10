<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PPDBGelombang;
use App\Models\PPDBPeriode;

class PpdbAdminController extends Controller
{
    public function index()
    {
        return view('ppdb.admin.index');
    }

    public function pendaftar()
    {
        return view('ppdb.admin.pendaftar');
    }

    public function bantuPendaftaran()
    {
        return view('ppdb.admin.bantu-pendaftaran');
    }

    public function verifikasi()
    {
        return view('ppdb.admin.verifikasi');
    }

    public function seleksi()
    {
        return view('ppdb.admin.seleksi');
    }

    public function pengumuman()
    {
        return view('ppdb.admin.pengumuman');
    }

    public function daftarUlang()
    {
        return view('ppdb.admin.daftar-ulang');
    }

    public function laporan()
    {
        return view('ppdb.admin.laporan');
    }

    public function pengaturan()
    {
        $totalPeriode = PPDBPeriode::count();
        $totalGelombang = PPDBGelombang::count();
        return view('ppdb.admin.pengaturan', compact(
            'totalPeriode',
            'totalGelombang'
        ));
    }
}
