<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PpdbOrangTuaController extends Controller
{
    public function dashboard()
    {
        return view('ppdb.orang-tua.dashboard');
    }

    public function dataSiswa()
    {
        return view('ppdb.orang-tua.data-siswa');
    }

    public function orangTua()
    {
        return view('ppdb.orang-tua.orang-tua');
    }

    public function alamat()
    {
        return view('ppdb.orang-tua.alamat');
    }

    public function pendidikan()
    {
        return view('ppdb.orang-tua.pendidikan');
    }

    public function berkas()
    {
        return view('ppdb.orang-tua.berkas');
    }

    public function review()
    {
        return view('ppdb.orang-tua.review');
    }

    public function status()
    {
        return view('ppdb.orang-tua.status');
    }

    public function buktiPendaftaran()
    {
        return view('ppdb.orang-tua.bukti-pendaftaran');
    }
}
